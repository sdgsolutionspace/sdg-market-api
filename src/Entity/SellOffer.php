<?php

namespace App\Entity;

use DateTime;
use DateInterval;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SellOffer.
 *
 * @ORM\Table(name="sell_offer", indexes={@ORM\Index(name="fk_trading_user_idx", columns={"seller_id"}), @ORM\Index(name="fk_sell_offer_project1_idx", columns={"project_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\SellOfferRepository")
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
     * @Assert\NotBlank()
     * @Assert\Type("numeric")
     */
    private $numberOfTokens;

    /**
     * @var string
     *
     * @ORM\Column(name="sell_price_per_token", type="decimal", precision=12, scale=2, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Type("numeric")
     */
    private $sellPricePerToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="offer_starts_utc_date", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     * @Assert\DateTime()
     */
    private $offerStartsUtcDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="offer_expires_at_utc_date", type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $offerExpiresAtUtcDate;

    /**
     * @var GitProject
     *
     * @ORM\ManyToOne(targetEntity="GitProject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * })
     * @Assert\NotNull()
     */
    private $project;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="seller_id", referencedColumnName="id")
     * })
     */
    private $seller;

    public function __construct()
    {
        $this->offerStartsUtcDate = new DateTime();
        $this->offerExpiresAtUtcDate = clone $this->offerStartsUtcDate;
        $this->offerExpiresAtUtcDate->add(new DateInterval('P7D'));
    }

    public function getId(): ? int
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

    public function getOfferStartsUtcDate(): ? \DateTimeInterface
    {
        return $this->offerStartsUtcDate;
    }

    public function setOfferStartsUtcDate($offerStartsUtcDate): self
    {
        $this->offerStartsUtcDate = $offerStartsUtcDate;

        return $this;
    }

    public function getOfferExpiresAtUtcDate(): ? \DateTimeInterface
    {
        return $this->offerExpiresAtUtcDate;
    }

    public function setOfferExpiresAtUtcDate(? \DateTimeInterface $offerExpiresAtUtcDate): self
    {
        $this->offerExpiresAtUtcDate = $offerExpiresAtUtcDate;

        return $this;
    }

    public function getProject(): ? GitProject
    {
        return $this->project;
    }

    public function setProject(? GitProject $project): self
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Return the seller.
     *
     * @return User
     */
    public function getSeller()
    {
        return $this->seller;
    }

    public function setSeller(? User $seller): self
    {
        $this->seller = $seller;

        return $this;
    }
}
