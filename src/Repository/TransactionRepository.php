<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\GitProject;
use App\Entity\Transaction;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function getProjectOwnValue(GitProject $project, User $user)
    {
        $positive = $this
            ->createQueryBuilder('t')
            ->select('sum(t.nbTokens)')
            ->leftJoin('t.toUser', 'toUser')
            ->where('t.project=:projectId')->setParameter('projectId', $project->getId())
            ->andWhere('toUser.id=:userId')->setParameter('userId', $user->getId())
            ->getQuery()
            ->getSingleScalarResult();

        $negative = $this
            ->createQueryBuilder('t')
            ->select('sum(t.nbTokens)')
            ->leftJoin('t.fromUser', 'fromUser')
            ->where('t.project=:projectId')->setParameter('projectId', $project->getId())
            ->andWhere('fromUser.id=:userId')->setParameter('userId', $user->getId())
            ->getQuery()
            ->getSingleScalarResult();

        return $positive - $negative;
    }

    public function findTransactionToUpdate()
    {
        return $this
            ->createQueryBuilder('t')
            ->select('t')
            ->where('t.id IN(SELECT pp.id FROM App:ProjectParticipation pp)')
            ->andWhere('t.toUser IS NULL')
            ->getQuery()
            ->getResult();
    }
}
