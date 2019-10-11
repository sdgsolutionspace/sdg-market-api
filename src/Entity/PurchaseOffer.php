<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PurchaseOffer.
 *
 * @ORM\Table(name="purchase_offer", indexes={@ORM\Index(name="fk_trading_user_idx", columns={"purchaser_id"}), @ORM\Index(name="fk_purchase_offer_project1_idx", columns={"project_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\PurchaseOfferRepository")
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
     * @var string
     *
     * @ORM\Column(name="number_of_tokens", type="decimal", precision=6, scale=2, nullable=false)
     * @Assert\Type("numeric")
     * @Assert\NotBlank()
     */
    private $numberOfTokens;

    /**
     * @var string
     *
     * @ORM\Column(name="purchase_price_per_token", type="decimal", precision=6, scale=2, nullable=false)
     * @Assert\Type("numeric")
     * @Assert\NotBlank()
     */
    private $purchasePricePerToken;

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
     * @Assert\NotBlank()
     */
    private $project;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="purchaser_id", referencedColumnName="id")
     * })
     */
    private $purchaser;

    /**
     * @var array
     *
     * @ORM\Column(name="goals", type="array", nullable=true)
     */
    private $goals = [];

    /**
     * @param $goal
     * 
     * @return string
     */
    public function goal()
    {
        return $this->goals;
    }

    /**
     * @return array
     */
    public function getGoals()
    {
        return $this->goals;
    }

     /**
     * @param $goal
     *
     * @return $this
     */
    public function addGoal($goal)
    {
        $this->goal = strtoupper($goal);
        if (!in_array($goal, $this->goals, true)) {
            // $this->goals[] = $goal;
            array_push($this->goals, $goal);
        }

        return $this;
    }

        /**
     * @param $goal
     *
     * @return $this
     */
    public function removeGoal($goal)
    {
        if (false !== $key = array_search(strtoupper($goal), $this->goals, true)) {
            unset($this->goals[$key]);
            $this->goals = array_values($this->goals);
        }

        return $this;
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

    public function getPurchasePricePerToken()
    {
        return $this->purchasePricePerToken;
    }

    public function setPurchasePricePerToken($purchasePricePerToken): self
    {
        $this->purchasePricePerToken = $purchasePricePerToken;

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

    public function setOfferExpiresAtUtcDate($offerExpiresAtUtcDate): self
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

    public function getPurchaser(): ? User
    {
        return $this->purchaser;
    }

    public function setPurchaser(? User $purchaser): self
    {
        $this->purchaser = $purchaser;

        return $this;
    }
}
