<?php

namespace App\Entity;

use DateTime;
use Exception;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AssertApp;

/**
 * Transaction.
 *
 * @ORM\Table(name="transaction", indexes={@ORM\Index(name="fk_transaction_user1_idx", columns={"from_user_id"}), @ORM\Index(name="fk_transaction_user2_idx", columns={"to_user_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 * @HasLifecycleCallbacks
 * @AssertApp\RemainToken(message="There is only {{ remaining }} tokens left. You cannot buy {{ value }} tokens.", groups={"purchase"})
 */
class Transaction
{
    const SUBSCRIPTION_SDG_CREDIT = 'Subription award';
    const CONTRIBUTION = 'Project contribution';
    const LABEL_TRADING = 'Trading';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="from_user_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $fromUser;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="to_user_id", referencedColumnName="id")
     * })
     * @Assert\NotNull()
     */
    private $toUser;

    /**
     * @var GitProject
     *
     * @ORM\ManyToOne(targetEntity="GitProject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $project;

    /**
     * @var SellOffer
     *
     * @ORM\ManyToOne(targetEntity="SellOffer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sell_offer_id", referencedColumnName="id", nullable=true)
     * })
     * @Assert\NotNull(groups={"purchase"})
     */
    private $sellOffer;

    /**
     * @var float
     *
     * @ORM\Column(name="nb_tokens", type="decimal", precision=8, scale=2, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Type("numeric", groups={"Default", "purchase"})
     * @Assert\NotNull(groups={"purchase"})
     */
    private $nbTokens = 0;

    /**
     * @var float
     *
     * @ORM\Column(name="nb_sdg", type="decimal", precision=8, scale=2, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Type("numeric")
     */
    private $nbSdg = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction_label", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $transactionLabel;

    /**
     * Check integrity with sell offer.
     *
     * @ORM\PrePersist
     */
    public function sellOfferPersist()
    {
        if ($this->sellOffer) {
            if (!$this->toUser) {
                throw new Exception('There is no user se to receive the tokens');
            }

            if ($this->sellOffer->getOfferExpiresAtUtcDate() <= new DateTime()) {
                //throw new Exception('The sell offer is expired and you can\'t buy them anymore');
            }

            $this->transactionLabel = self::LABEL_TRADING;
            $this->fromUser = $this->sellOffer->getSeller();
            $this->project = $this->sellOffer->getProject();
            $this->nbTokens = abs($this->nbTokens);
            $this->nbSdg = -1 * $this->nbTokens * $this->sellOffer->getSellPricePerToken();
        }
    }

    public function getId(): ? int
    {
        return $this->id;
    }

    public function getMovementType(): ? string
    {
        return $this->movementType;
    }

    public function setMovementType(string $movementType): self
    {
        $this->movementType = $movementType;

        return $this;
    }

    /**
     * Get the value of fromUser.
     *
     * @return User
     */
    public function getFromUser(): ? User
    {
        return $this->fromUser;
    }

    /**
     * Set the value of fromUser.
     *
     * @param User $fromUser
     *
     * @return self
     */
    public function setFromUser(User $fromUser): self
    {
        $this->fromUser = $fromUser;

        return $this;
    }

    /**
     * Get the value of toUser.
     *
     * @return User
     */
    public function getToUser(): ? User
    {
        return $this->toUser;
    }

    /**
     * Set the value of toUser.
     *
     * @param User $toUser
     *
     * @return self
     */
    public function setToUser(User $toUser): self
    {
        $this->toUser = $toUser;

        return $this;
    }

    /**
     * Get the value of nbTokens.
     *
     * @return float
     */
    public function getNbTokens()
    {
        return $this->nbTokens;
    }

    /**
     * Set the value of nbTokens.
     *
     * @param float $nbTokens
     *
     * @return self
     */
    public function setNbTokens($nbTokens)
    {
        $this->nbTokens = $nbTokens;

        return $this;
    }

    /**
     * Get the value of nbSdg.
     *
     * @return float
     */
    public function getNbSdg()
    {
        return $this->nbSdg;
    }

    /**
     * Set the value of nbSdg.
     *
     * @param float $nbSdg
     *
     * @return self
     */
    public function setNbSdg($nbSdg)
    {
        $this->nbSdg = $nbSdg;

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
     * Get the value of transactionLabel.
     *
     * @return string
     */
    public function getTransactionLabel()
    {
        return $this->transactionLabel;
    }

    /**
     * Set the value of transactionLabel.
     *
     * @param string $transactionLabel
     *
     * @return self
     */
    public function setTransactionLabel(string $transactionLabel)
    {
        $this->transactionLabel = $transactionLabel;

        return $this;
    }

    /**
     * Get the value of sellOffer.
     *
     * @return SellOffer
     */
    public function getSellOffer(): ? SellOffer
    {
        return $this->sellOffer;
    }

    /**
     * Set the value of sellOffer.
     *
     * @param SellOffer $sellOffer
     *
     * @return self
     */
    public function setSellOffer(SellOffer $sellOffer): self
    {
        $this->sellOffer = $sellOffer;

        return $this;
    }
}
