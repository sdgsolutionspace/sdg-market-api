<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SellOffer
 *
 * @ORM\Table(name="sell_offer", indexes={@ORM\Index(name="fk_trading_user_idx", columns={"seller_id"}), @ORM\Index(name="fk_sell_offer_project1_idx", columns={"project_id"})})
 * @ORM\Entity
 */
class SellOffer
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
     * @var int
     *
     * @ORM\Column(name="seller_id", type="integer", nullable=false)
     */
    private $sellerId;

    /**
     * @var string
     *
     * @ORM\Column(name="number_of_tokens", type="decimal", precision=6, scale=2, nullable=false)
     */
    private $numberOfTokens;

    /**
     * @var string
     *
     * @ORM\Column(name="sell_price_per_token", type="decimal", precision=6, scale=2, nullable=false)
     */
    private $sellPricePerToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="offer_stats_utc_date", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $offerStatsUtcDate = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="offer_expires_at_utc_date", type="datetime", nullable=false, options={"default"="0000-00-00 00:00:00"})
     */
    private $offerExpiresAtUtcDate = '0000-00-00 00:00:00';

    /**
     * @var \GitProject
     *
     * @ORM\ManyToOne(targetEntity="GitProject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * })
     */
    private $project;

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
     * @return int
     */
    public function getSellerId()
    {
        return $this->sellerId;
    }

    /**
     * @param int $sellerId
     */
    public function setSellerId($sellerId)
    {
        $this->sellerId = $sellerId;
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
     * @return string
     */
    public function getSellPricePerToken()
    {
        return $this->sellPricePerToken;
    }

    /**
     * @param string $sellPricePerToken
     */
    public function setSellPricePerToken($sellPricePerToken)
    {
        $this->sellPricePerToken = $sellPricePerToken;
    }

    /**
     * @return \DateTime
     */
    public function getOfferStatsUtcDate()
    {
        return $this->offerStatsUtcDate;
    }

    /**
     * @param \DateTime $offerStatsUtcDate
     */
    public function setOfferStatsUtcDate($offerStatsUtcDate)
    {
        $this->offerStatsUtcDate = $offerStatsUtcDate;
    }

    /**
     * @return \DateTime
     */
    public function getOfferExpiresAtUtcDate()
    {
        return $this->offerExpiresAtUtcDate;
    }

    /**
     * @param \DateTime $offerExpiresAtUtcDate
     */
    public function setOfferExpiresAtUtcDate($offerExpiresAtUtcDate)
    {
        $this->offerExpiresAtUtcDate = $offerExpiresAtUtcDate;
    }

    /**
     * @return \GitProject
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param \GitProject $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }


}
