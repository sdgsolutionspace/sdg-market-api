<?php

namespace App\Repository;

use App\Entity\PurchaseOffer;
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
        $qb = $this->createQueryBuilder('so')->where("so.numberOfTokens > 0");

        if ($project) {
            $qb->andWhere('so.project = :project')->setParameter('project', $project);
        }

        if (!$includeExpired) {
            $qb->andWhere('so.offerExpiresAtUtcDate >= :now')->setParameter('now', new DateTime());
        }

        $return = [];
        foreach ($qb->getQuery()->getResult() as $currentOffer) {
            $offer = [
                'id' => $currentOffer->getId(),
                'number_of_tokens' => $currentOffer->getNumberOfTokens(),
                'sell_price_per_token' => $currentOffer->getSellPricePerToken(),
                'offer_starts_utc_date' => $currentOffer->getOfferStartsUtcDate(),
                'offer_expires_at_utc_date' => $currentOffer->getOfferExpiresAtUtcDate(),
                'project' => $currentOffer->getProject(),
                'seller' => $currentOffer->getSeller(),
                'remaining_tokens' => $this->getRemainingTokensForOffer($currentOffer),
            ];

            if ($includeExpired || $offer['remaining_tokens'] > 0) {
                $return[] = $offer;
            }
        }

        return $return;
    }

    public function getRemainingTokensForOffer(SellOffer $sellOffer)
    {
        return (float) $this->getEntityManager()->getRepository('App:Transaction')->getRemainingTokensForOffer($sellOffer);
    }


    public function findMatchableOffers(PurchaseOffer $purchaseOffer)
    {
        $qb = $this->createQueryBuilder('so');
        $qb->where("so.sellPricePerToken <= :purchaseOfferPrice")
            ->andWhere("so.project = :project")
            ->andWhere("so.offerExpiresAtUtcDate >= :now")
            ->orderBy("so.sellPricePerToken", "ASC")
            ->addOrderBy("so.offerStartsUtcDate", "ASC")
            ->setParameter("purchaseOfferPrice", $purchaseOffer->getPurchasePricePerToken())
            ->setParameter("project", $purchaseOffer->getProject())
            ->setParameter("now", new DateTime());

        return $qb->getQuery()->getResult();
    }
}
