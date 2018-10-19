<?php

namespace App\Command;

use App\Entity\User;
use App\Entity\ProjectParticipation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AssignParticipationUserCommand extends Command
{
    protected static $defaultName = 'app:assign-participation-user';
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Assign the participations to the proper user to give them their tokens');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Assigning the token to proper users');

        $unlinkedParticipations = $this->entityManager->getRepository('App:ProjectParticipation')->findBy([
            'user' => null,
        ]);

        foreach ($unlinkedParticipations as $currentParticipation) {
            if ($currentParticipation->getCommitterUsername()) {
                $user = $this->entityManager->getRepository('App:User')->findOneBy([
                    'username' => $currentParticipation->getCommitterUsername(),
                ]);

                if ($user) {
                    $this->assignParticipationToUser($io, $currentParticipation, $user);
                    continue;
                }
            }

            if ($currentParticipation->getCommitterEmail()) {
                $user = $this->entityManager->getRepository('App:User')->findOneBy([
                    'email' => $currentParticipation->getCommitterEmail(),
                ]);

                if ($user) {
                    $this->assignParticipationToUser($io, $currentParticipation, $user);
                    continue;
                }
            }
        }

        $io->success('Assignation completed');
    }

    protected function assignParticipationToUser(SymfonyStyle $io, ProjectParticipation $participation, User $user)
    {
        $io->note(sprintf('Assigning commit %s to user %s', $participation->getCommitId(), $user->getUsername()));
        $participation->setUser($user);
        $this->entityManager->persist($participation);
        $this->entityManager->flush();
    }
}
