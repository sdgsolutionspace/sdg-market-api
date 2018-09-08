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
     * @var \DateTime|null
     *
     * @ORM\Column(name="offer_expires_at_utc_date", type="datetime", nullable=true)
     */
    private $offerExpiresAtUtcDate;

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
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="seller_id", referencedColumnName="id")
     * })
     */
    private $seller;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberOfTokens()
    {
        return $this->numberOfTokens;
    }

    public function setNumberOfTokens($numberOfTokens): self
    {
        $this->numberOfTokens = $numberOfTokens;

        return $this;
    }

    public function getSellPricePerToken()
    {
        return $this->sellPricePerToken;
    }

    public function setSellPricePerToken($sellPricePerToken): self
    {
        $this->sellPricePerToken = $sellPricePerToken;

        return $this;
    }

    public function getOfferStatsUtcDate(): ?\DateTimeInterface
    {
        return $this->offerStatsUtcDate;
    }

    public function setOfferStatsUtcDate(\DateTimeInterface $offerStatsUtcDate): self
    {
        $this->offerStatsUtcDate = $offerStatsUtcDate;

        return $this;
    }

    public function getOfferExpiresAtUtcDate(): ?\DateTimeInterface
    {
        return $this->offerExpiresAtUtcDate;
    }

    public function setOfferExpiresAtUtcDate(?\DateTimeInterface $offerExpiresAtUtcDate): self
    {
        $this->offerExpiresAtUtcDate = $offerExpiresAtUtcDate;

        return $this;
    }

    public function getProject(): ?GitProject
    {
        return $this->project;
    }

    public function setProject(?GitProject $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getSeller(): ?User
    {
        return $this->seller;
    }

    public function setSeller(?User $seller): self
    {
        $this->seller = $seller;

        return $this;
    }


}
