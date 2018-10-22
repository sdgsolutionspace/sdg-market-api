<?php

namespace App\Repository;

use DateTime;
use App\Entity\PurchaseOffer;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class PurchaseOfferRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PurchaseOffer::class);
    }

    public function findFiltered($project, $includeExpired)
    {
        $qb = $this->createQueryBuilder('po');

        if ($project) {
            $qb->where('po.project = :project')->setParameter('project', $project);
        }

        if (!$includeExpired) {
            $qb->andWhere('po.offerExpiresAtUtcDate >= :now')->setParameter('now', new DateTime());
        }

        return $qb->getQuery()->getResult();
    }
}
