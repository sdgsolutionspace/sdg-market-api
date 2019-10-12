<?php

namespace App\Repository;

use DateTime;
use App\Entity\SellOffer;
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
        $qb = $this->createQueryBuilder('po')->where("po.numberOfTokens > 0");

        if ($project) {
            $qb->andWhere('po.project = :project')->setParameter('project', $project);
        }

        if (!$includeExpired) {
            $qb->andWhere('po.offerExpiresAtUtcDate >= :now')->setParameter('now', new DateTime());
        }

        return $qb->getQuery()->getResult();
    }

    public function findMatchableOffers(SellOffer $sellOffer)
    {
        $qb = $this->createQueryBuilder('po');
        $qb->where("po.purchasePricePerToken >= :sellPricePerToken")
            ->andWhere("po.project = :project")
            ->andWhere("po.offerExpiresAtUtcDate >= :now")
            ->orderBy("po.purchasePricePerToken", "DESC")
            ->addOrderBy("po.offerStartsUtcDate", "ASC")
            ->setParameter("sellPricePerToken", $sellOffer->getSellPricePerToken())
            ->setParameter("project", $sellOffer->getProject())
            ->setParameter("now", new DateTime());

        return $qb->getQuery()->getResult();
    }
}
