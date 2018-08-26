<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trading
 *
 * @ORM\Table(name="trading", indexes={@ORM\Index(name="fk_trading_sell_offer1_idx", columns={"sell_offer_id"})})
 * @ORM\Entity
 */
class Trading
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="transaction_utc_datetime", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $transactionUtcDatetime = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="number_of_tokens", type="decimal", precision=8, scale=2, nullable=false)
     */
    private $numberOfTokens;

    /**
     * @var \SellOffer
     *
     * @ORM\ManyToOne(targetEntity="SellOffer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sell_offer_id", referencedColumnName="id")
     * })
     */
    private $sellOffer;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getTransactionUtcDatetime()
    {
        return $this->transactionUtcDatetime;
    }

    /**
     * @param \DateTime $transactionUtcDatetime
     */
    public function setTransactionUtcDatetime($transactionUtcDatetime)
    {
        $this->transactionUtcDatetime = $transactionUtcDatetime;
    }

    /**
     * @return string
     */
    public function getNumberOfTokens()
    {
        return $this->numberOfTokens;
    }

    /**
     * @param string $numberOfTokens
     */
    public function setNumberOfTokens($numberOfTokens)
    {
        $this->numberOfTokens = $numberOfTokens;
    }

    /**
     * @return \SellOffer
     */
    public function getSellOffer()
    {
        return $this->sellOffer;
    }

    /**
     * @param \SellOffer $sellOffer
     */
    public function setSellOffer($sellOffer)
    {
        $this->sellOffer = $sellOffer;
    }




}
