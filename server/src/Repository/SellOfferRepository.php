<?php

namespace App\Repository;

use DateTime;
use App\Entity\SellOffer;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class SellOfferRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SellOffer::class);
    }

    public function findFiltered($project, $includeExpired)
    {
        $qb = $this->createQueryBuilder('so');

        if ($project) {
            $qb->where('so.project = :project')->setParameter('project', $project);
        }

        if (!$includeExpired) {
            $qb->where('so.offerExpiresAtUtcDate >= :now')->setParameter('now', new DateTime());
        }

        return $qb->getQuery()->getResult();
    }
}
