<?php

namespace App\Validator;

use App\Entity\SellOffer;
use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RemainTokenValidator extends ConstraintValidator
{
    /**
     * Entity manager.
     *
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint App\Validator\RemainToken */
        /* @var $value Transaction */

        $offer = $value->getSellOffer();
        $requestedTokens = $value->getNbTokens();

        $tokensLeft = $this->getRemainingTokens($offer);
        $enoughTokensRemaining = $tokensLeft >= $requestedTokens;


        if (!$enoughTokensRemaining) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $requestedTokens)
                ->setParameter('{{ remaining }}', $tokensLeft)
                ->atPath('nbTokens')
                ->addViolation();
        }
    }

    protected function getRemainingTokens(SellOffer $offer = null)
    {
        if (!$offer) {
            return 0;
        }

        return $this->em->getRepository(SellOffer::class)->getRemainingTokensForOffer($offer);
    }
}
