<?php

namespace App\Service;

use DateTime;
use App\Entity\SellOffer;
use App\Entity\Transaction;
use App\Entity\PurchaseOffer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 * This service is used for auto matching of transaction purpose
 */
class AutoMatchTransaction
{
    /**
     * Entity manager
     *
     * @var EntityManager $em
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function autoMatchSell(SellOffer $sellOffer)
    {
        // Get the selling offer elligibles for the automatch
        $matchables = $this->em->getRepository(PurchaseOffer::class)->findMatchableOffers($sellOffer);

        // Total of token to be auto sold if availables
        $tokensToSell = $sellOffer->getNumberOfTokens();

        foreach ($matchables as $purchaseOffer) {
            /** @var PurchaseOffer $purchaseOffer */
            $stillSellable = $tokensToSell;

            // If no token remain, then go to the next sell offer
            if ($purchaseOffer->getNumberOfTokens() <= 0) {
                continue;
            }

            // Take only the remaining number of tokens if their are not enough token for the purchase
            if ($stillSellable >= $purchaseOffer->getNumberOfTokens()) {
                $stillSellable = $purchaseOffer->getNumberOfTokens();
            }
            $tokensToSell -= $stillSellable;

            // If no more token to sell, we break the loop
            if (!$stillSellable) {
                break;
            }

            // Decrease the number of tokens remaining after the auto match
            $sellOffer->setNumberOfTokens(
                $sellOffer->getNumberOfTokens() - $stillSellable
            );

            // Decrease the purchase offer
            $purchaseOffer->setNumberOfTokens(
                $purchaseOffer->getNumberOfTokens() - $stillSellable
            );


            if ($purchaseOffer->getNumberOfTokens() < 0) {
                $purchaseOffer->setOfferExpiresAtUtcDate(new DateTime());
            }

            if ($sellOffer->getNumberOfTokens() < 0) {
                $sellOffer->setOfferExpiresAtUtcDate(new DateTime());
                $this->em->persist($sellOffer);
            }

            // Create the automatic transaction
            $transaction = new Transaction();


            // Create sell offer specific to the purchase offer
            $specificSellOffer = clone $sellOffer;
            $specificSellOffer->setSellPricePerToken($purchaseOffer->getPurchasePricePerToken());

            $transaction
                ->setCreatedAt(new DateTime())
                ->setFromUser($specificSellOffer->getSeller())
                ->setToUser($purchaseOffer->getPurchaser())
                ->setProject($purchaseOffer->getProject())
                ->setNbTokens($stillSellable)
                ->setSellOffer($specificSellOffer);

            $this->em->persist($purchaseOffer);
            $this->em->persist($specificSellOffer);
            $this->em->persist($transaction);
        }

        $this->em->flush();
        return $sellOffer;
    }

    /**
     * Authmatch a purchase offer with all existing selling offers
     *
     * @param PurchaseOffer $purchaseOffer
     * @return PurchaseOffer reduced with the number of auto matched tokens
     */
    public function autoMatchPurchase(PurchaseOffer $purchaseOffer): PurchaseOffer
    {
        // Get the selling offer elligibles for the automatch
        $matchables = $this->em->getRepository(SellOffer::class)->findMatchableOffers($purchaseOffer);
        // Total of token to be auto bought if availables
        $neededTokens = $purchaseOffer->getNumberOfTokens();

        foreach ($matchables as $sellOffer) {
            /** @var SellOffer $sellOffer */
            $tokensToBuy = $neededTokens;

            // Get the number of remaining token for the sell offer
            $remaining = $this->em->getRepository(Transaction::class)->getRemainingTokensForOffer($sellOffer);

            // If no token remain, then go to the next sell offer
            if ($remaining <= 0) {
                continue;
            }

            // Take only the remaining number of tokens if their are not enough token for the purchase
            if ($tokensToBuy >= $remaining) {
                $tokensToBuy = $remaining;
            }
            $neededTokens -= $tokensToBuy;

            // If no more token to buy, we break the loop
            if (!$tokensToBuy) {
                break;
            }

            // Decrease the number of tokens remaining after the auto match
            $purchaseOffer->setNumberOfTokens(
                $purchaseOffer->getNumberOfTokens() - $tokensToBuy
            );

            if ($purchaseOffer->getNumberOfTokens() < 0) {
                $purchaseOffer->setOfferExpiresAtUtcDate(new DateTime());
            }

            // Create the automatic transaction
            $transaction = new Transaction();
            $transaction
                ->setCreatedAt(new DateTime())
                ->setFromUser($sellOffer->getSeller())
                ->setToUser($purchaseOffer->getPurchaser())
                ->setProject($purchaseOffer->getProject())
                ->setNbTokens($tokensToBuy)
                ->setSellOffer($sellOffer);

            $this->em->persist($transaction);
        }

        $this->em->flush();
        return $purchaseOffer;
    }
}
