<?php

namespace App\Repository;

use App\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findUserWithSold(User $user)
    {
        $positive = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->from('App:Transaction', 't')
            ->leftJoin('t.toUser', 'toUser')
            ->select('sum(t.nbSdg)')
            ->where('toUser.id=:userId')->setParameter('userId', $user->getId())
            ->getQuery()
            ->getSingleScalarResult();

        $negative = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->from('App:Transaction', 't')
            ->leftJoin('t.fromUser', 'fromUser')
            ->select('sum(t.nbSdg)')
            ->where('fromUser.id=:userId')->setParameter('userId', $user->getId())
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
            'sold_sdg' => $positive - $negative,
        ];
    }
}
