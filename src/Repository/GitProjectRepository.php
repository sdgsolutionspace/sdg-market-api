<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\GitProject;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class GitProjectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GitProject::class);
    }

    public function findAllWithPersonalValue(User $user = null)
    {
        $projects = $qb = $this
            ->createQueryBuilder('gp')
            ->select('gp, 0 as my_contribution')
            ->getQuery()
            ->getResult();

        if (!$user) {
            return $projects;
        }

        for ($i = 0; $i < count($projects); ++$i) {
            $currentProject = $projects[$i][0];
            $yourValue = $this->getEntityManager()->getRepository('App:Transaction')->getProjectOwnValue($currentProject, $user);
            $projects[$i]['my_contribution'] = $yourValue;
        }

        return $projects;
    }
}
