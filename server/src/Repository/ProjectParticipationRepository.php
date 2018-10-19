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

    public function findFiltered($project)
    {
        $qb = $this->createQueryBuilder('pp');

        if ($project) {
            $qb->where('pp.gitProject = :project')->setParameter('project', $project);
        }

        return $qb->getQuery()->getResult();
    }
}
