<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Transaction.
 *
 * @ORM\Table(name="transaction", indexes={@ORM\Index(name="fk_transaction_user1_idx", columns={"from_user_id"}), @ORM\Index(name="fk_transaction_user2_idx", columns={"to_user_id"})})
 * @ORM\Entity
 */
class Transaction
{
    const SUBSCRIPTION_SDG_CREDIT = 'Subription award';

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
     * @var GitProject
     *
     * @ORM\ManyToOne(targetEntity="ProjectParticipation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_participation_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $projectParticipation;

    /**
     * @var float
     *
     * @ORM\Column(name="nb_tokens", type="decimal", precision=8, scale=2, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Type("numeric")
     */
    private $nbTokens;

    /**
     * @var float
     *
     * @ORM\Column(name="nb_sdg", type="decimal", precision=8, scale=2, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Type("numeric")
     */
    private $nbSdg;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction_label", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $transactionLabel;

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
     * Get the value of projectParticipation.
     *
     * @return GitProject
     */
    public function getProjectParticipation()
    {
        return $this->projectParticipation;
    }

    /**
     * Set the value of projectParticipation.
     *
     * @param GitProject $projectParticipation
     *
     * @return self
     */
    public function setProjectParticipation(GitProject $projectParticipation)
    {
        $this->projectParticipation = $projectParticipation;

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
}
