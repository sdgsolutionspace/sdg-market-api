<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PurchaseOffer
 *
 * @ORM\Table(name="purchase_offer", indexes={@ORM\Index(name="fk_trading_user_idx", columns={"purchaser_id"}), @ORM\Index(name="fk_purchase_offer_project1_idx", columns={"project_id"})})
 * @ORM\Entity
 */
class PurchaseOffer
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
     * @ORM\Column(name="purchaser_id", type="integer", nullable=false)
     */
    private $purchaserId;

    /**
     * @var string
     *
     * @ORM\Column(name="number_of_tokens", type="decimal", precision=6, scale=2, nullable=false)
     */
    private $numberOfTokens;

    /**
     * @var string
     *
     * @ORM\Column(name="purchase_price_per_token", type="decimal", precision=6, scale=2, nullable=false)
     */
    private $purchasePricePerToken;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="offer_starts_utc_date", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $offerStartsUtcDate = 'CURRENT_TIMESTAMP';

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
    public function getPurchaserId()
    {
        return $this->purchaserId;
    }

    /**
     * @param int $purchaserId
     */
    public function setPurchaserId($purchaserId)
    {
        $this->purchaserId = $purchaserId;
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
    public function getPurchasePricePerToken()
    {
        return $this->purchasePricePerToken;
    }

    /**
     * @param string $purchasePricePerToken
     */
    public function setPurchasePricePerToken($purchasePricePerToken)
    {
        $this->purchasePricePerToken = $purchasePricePerToken;
    }

    /**
     * @return \DateTime
     */
    public function getOfferStartsUtcDate()
    {
        return $this->offerStartsUtcDate;
    }

    /**
     * @param \DateTime $offerStartsUtcDate
     */
    public function setOfferStartsUtcDate($offerStartsUtcDate)
    {
        $this->offerStartsUtcDate = $offerStartsUtcDate;
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
