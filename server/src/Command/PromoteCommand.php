<?php

namespace App\Command;

use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PromoteCommand extends Command
{
    protected static $defaultName = 'app:promote';

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
            ->setDescription('Add ROLE_ADMIN to a specific user')
            ->addArgument('username', InputArgument::REQUIRED, 'The user to promote');
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

            if ($user->hasRole('ROLE_ADMIN')) {
                $io->note(sprintf('User %s has already role ROLE_ADMIN', $username));
            } else {
                $user->addRole('ROLE_ADMIN');
                $this->em->persist($user);
                $this->em->flush();
                $io->success(sprintf('User %s has been promoted to ROLE_ADMIN', $username));
            }
        } catch (Exception $e) {
            $io->error($e->getMessage());
        }
    }
}
