<?php

namespace App\Command;

use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DemoteCommand extends Command
{
    protected static $defaultName = 'app:demote';

    /**
     * Entity manager.
     *
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Remove ROLE_ADMIN to a specific user')
            ->addArgument('username', InputArgument::REQUIRED, 'The user to demote');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('username');

        try {
            $io->note(sprintf('Promoting user %s', $username));
            $user = $this->em->getRepository('App:User')->findOneBy([
                'username' => $username,
            ]);

            if (!$user->hasRole('ROLE_ADMIN')) {
                $io->note(sprintf('User %s has no ROLE_ADMIN', $username));
            } else {
                $user->removeRole('ROLE_ADMIN');
                $this->em->persist($user);
                $this->em->flush();
                $io->success(sprintf('Role ROLE_ADMIN was removed for user %s', $username));
            }
        } catch (Exception $e) {
            $io->error($e->getMessage());
        }
    }
}
