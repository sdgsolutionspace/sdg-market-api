<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\GitProject;
use Doctrine\ORM\Query\Expr;
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
        $qb = $this
            ->createQueryBuilder('gp')
            ->select('gp, p')
            ->leftJoin('gp.participations', 'p')
            ->leftJoin('p.user', 'u');

        if ($user) {
            $qb = $this
                ->createQueryBuilder('gp')
                ->select('gp, p, sum(p.numberOfTokens) as my_contribution')
                ->leftJoin('gp.participations', 'p')
                ->leftJoin('p.user', 'u', Expr\Join::WITH, 'u.id=:userId')->setParameter('userId', $user->getId())
                ->groupBy('gp.id, u.id');
        } else {
            $qb = $this
                ->createQueryBuilder('gp')
                ->select('gp, 0 as my_contribution');
        }

        return $qb->getQuery()->getResult();
    }
}
