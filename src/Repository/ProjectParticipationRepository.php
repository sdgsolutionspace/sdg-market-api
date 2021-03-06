<?php

namespace App\Repository;

use App\Entity\ProjectParticipation;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ProjectParticipationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProjectParticipation::class);
    }

    public function findFiltered($project, $user)
    {
        $qb = $this->createQueryBuilder('pp');

        if ($project) {
            $qb->where('pp.gitProject = :project')->setParameter('project', $project);
        }

        if ($user) {
            $qb->leftJoin('pp.transaction', 't');
            $qb->leftJoin('t.toUser', 'toUser');
            $qb->where('toUser.id = :user')->setParameter('user', $user);
        }

        return $qb->getQuery()->getResult();
    }

    public function findProjectParticipationToUpdate()
    {
        return $this
            ->createQueryBuilder('pp')
            ->select('pp, t')
            ->leftJoin('pp.transaction', 't')
            ->where('t.toUser IS NULL')
            ->getQuery()
            ->getResult();
    }
}
