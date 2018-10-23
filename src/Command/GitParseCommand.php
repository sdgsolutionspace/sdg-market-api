<?php

namespace App\Command;

use DateTime;
use DateTimeZone;
use Github\Client;
use App\Entity\GitProject;
use App\Entity\Transaction;
use App\Entity\ProjectParticipation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GitParseCommand extends Command
{
    protected static $defaultName = 'app:git-parse';
    /**
     * Entity manager.
     *
     * @var EntityManagerInterface
     */
    protected $entityManager;
    protected $githubClient;

    public function __construct(EntityManagerInterface $entityManager, Client $githubClient)
    {
        $this->entityManager = $entityManager;
        $this->githubClient = $githubClient;
        $this->githubClient->authenticate(getenv('GITHUB_PARSER_LOGIN'), getenv('GITHUB_PARSER_PASSWORD'));
        parent::__construct();
    }

    /**
     * Configure the command.
     */
    protected function configure()
    {
        $this
            ->setDescription('Get all the commit from the different commits in order to calculate their values and assign token to users');
        //->addOption('git-project', null, InputOption::VALUE_NONE, 'GIT project id to parse');
    }

    protected function dateTimeFromJsonFormat($date)
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime($date)));
        $date->setTimezone(new DateTimeZone('UTC'));

        return $date;
    }

    /**
     * Execute the command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        // $gitProject = $input->getArgument('git-project');
        // $io->note(sprintf('You passed an argument: %s', $arg1));

        $io->title('Parsing the git commits');

        $gitProjects = $this->entityManager->getRepository('App:GitProject')->findAll();

        foreach ($gitProjects as $gitProject) {
            $io->section(sprintf('Browsing project %s', $gitProject->getName()));
            $gitParams = $this->parseGithubUrl($gitProject->getGitAddress());
            if (!$gitParams) {
                $io->note(sprintf('Git %s is not a github repository, ignoring it', $gitProject->getGitAddress()));
                continue;
            }
            $commits = $this->githubClient->api('repo')->commits()->all($gitParams['username'], $gitParams['project'], array('sha' => 'master'));
            foreach ($commits as $commit) {
                $commitData = $this->extractUsefulData($commit);
                $io->note(sprintf('Parsing commit %s from user %s', $commitData['sha'], $commitData['committer']['username']));

                $changes = $this->getCommitDetails($gitParams['username'], $gitParams['project'], $commitData['sha']);

                $nbTokens = $this->getTokens($gitProject, $changes['total-levenstein']);

                $io->note(sprintf('=> Levenstein value for user %s is %s. Granting him %s tokens', $changes['total-levenstein'], $commitData['committer']['email'], $nbTokens));

                $tokensGenerated = $this->grantUserTokens($gitProject, $commitData['committer']['email'], $commitData['committer']['username'], $commitData['sha'], $commitData['commit_date'], $nbTokens);

                if ($tokensGenerated) {
                    $io->note(sprintf('=> The project value is now %s', $gitProject->getProjectValue()));
                }
            }

            $this->entityManager->persist($gitProject);
        }

        $this->entityManager->flush();

        $io->success('Git parsed successfully.');
    }

    /**
     * Generate the project participation in the database.
     *
     * @param GitProject $project
     * @param string     $email
     * @param string     $commitId
     * @param string     $commitDate Javascript format
     * @param int        $nbTokens
     *
     * @return bool
     */
    protected function grantUserTokens(GitProject $gitProject, $email, $username, $commitId, $commitDate, $nbTokens)
    {
        $existingContribution = $this->getExistingContribution($commitId);

        // If the contribution does already exist, we do nothing
        if ($existingContribution) {
            return false;
        }

        $transaction = new Transaction();
        // Create the SDG offer
        $transaction
            ->setNbTokens($nbTokens)
            ->setTransactionLabel(Transaction::CONTRIBUTION)
            ->setProject($gitProject);
        $this->entityManager->persist($transaction);

        // Create a new participation in the database, if the contribution does not exist yet
        $projectParticipation = new ProjectParticipation();
        $projectParticipation
            ->setCalculationUtcDatetime(new DateTime())
            ->setCommitDate($this->dateTimeFromJsonFormat($commitDate))
            ->setCommitId($commitId)
            ->setGitProject($gitProject)
            ->setTransaction($transaction)
            //->setNumberOfTokens($nbTokens)
            ->setCommitterEmail($email)
            ->setCommitterUsername($username);

        $this->entityManager->persist($projectParticipation);

        // Increment global project value
        $gitProject->incrementProjectValue($nbTokens);
    }

    protected function getExistingContribution($sha)
    {
        return $this->entityManager->getRepository('App:ProjectParticipation')->findOneBy([
            'commitId' => $sha,
        ]);
    }

    protected function getTokens(GitProject $gitProject, $levenstein)
    {
        $currentProjectValue = $gitProject->getProjectValue();
        $tokens = 100 * $levenstein / $gitProject->getProjectValue();

        return $tokens;
    }

    /**
     * Get the details of the commit (list of updated files with the number of change and the levenstein distance for each file).
     *
     * TODO : Currently, binary files are not giving any rewards to the users. It should be nice to find a way to reward users who are sending binary files
     *
     * @param string $username
     * @param string $project
     * @param string $sha
     */
    public function getCommitDetails($username, $project, $sha)
    {
        $data = [
            'details' => [],
            'total-levenstein' => 0,
        ];

        if ($this->getExistingContribution($sha)) {
            return $data;
        }

        $details = $this->githubClient->api('repo')->commits()->show($username, $project, $sha);

        foreach ($details['files'] as $file) {
            $data['details'][] = [
                'new_file' => $file['status'] == 'added' && empty($file['patch']),
                'filename' => $file['filename'],
                'binary' => empty($file['patch']),
                'changes' => $file['changes'],
                'levensteinPatch' => empty($file['patch']) ? 0 : $this->levensteinPatch($file['patch']),
            ];

            $data['total-levenstein'] += $data['details'][count($data['details']) - 1]['levensteinPatch'];
        }

        return $data;
    }

    /**
     * Return the levenstein distance for patch passed in the argument.
     *
     * @param string $patch
     *
     * @return int
     */
    protected function levensteinPatch($patch)
    {
        if (empty($patch)) {
            return 0;
        }

        $patchArray = explode("\n", $patch);
        $levenstein = 0;

        foreach ($patchArray as $line) {
            if (preg_match('/^\+/', $line)) {
                $levenstein += strlen($line) - 1;
            }
            if (preg_match('/^\-/', $line)) {
                $levenstein -= strlen($line) - 1;
            }
        }

        return abs($levenstein);
    }

    /**
     * Get username and project name from the github url.
     *
     * @param string $url
     */
    protected function parseGithubUrl($url)
    {
        preg_match("#//github.com/([a-z0-9\-\._]+)/([a-z0-9\-\._]+)+#i", $url, $matches);
        if (count($matches) === 3) {
            return [
                'username' => $matches[1],
                'project' => $matches[2],
            ];
        } else {
            return null;
        }
    }

    /**
     * Extract the data needed from the github api response.
     *
     * @param array $commit
     *
     * @return array
     */
    protected function extractUsefulData($commit)
    {
        $data = [
            'sha' => $commit['sha'],
            'committer' => [
                'email' => $commit['commit']['committer']['email'],
                'username' => $commit['committer']['login'],
                'user-id' => $commit['committer']['id'],
            ],
            'commit_date' => $commit['commit']['committer']['date'],
        ];

        return $data;
    }
}
