<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table(name="transaction", indexes={@ORM\Index(name="fk_transaction_project_participation1_idx", columns={"project_participation_id"}), @ORM\Index(name="fk_transaction_trading1_idx", columns={"trading_id"}), @ORM\Index(name="fk_transaction_user1_idx", columns={"user_id"})})
 * @ORM\Entity
 */
class Transaction
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
     * @ORM\Column(name="movement_type", type="string", length=0, nullable=false)
     */
    private $movementType;

    /**
     * @var ProjectParticipation
     *
     * @ORM\ManyToOne(targetEntity="ProjectParticipation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_participation_id", referencedColumnName="id")
     * })
     */
    private $projectParticipation;

    /**
     * @var Trading
     *
     * @ORM\ManyToOne(targetEntity="Trading")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="trading_id", referencedColumnName="id")
     * })
     */
    private $trading;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMovementType(): ?string
    {
        return $this->movementType;
    }

    public function setMovementType(string $movementType): self
    {
        $this->movementType = $movementType;

        return $this;
    }

    public function getProjectParticipation(): ?ProjectParticipation
    {
        return $this->projectParticipation;
    }

    public function setProjectParticipation(?ProjectParticipation $projectParticipation): self
    {
        $this->projectParticipation = $projectParticipation;

        return $this;
    }

    public function getTrading(): ?Trading
    {
        return $this->trading;
    }

    public function setTrading(?Trading $trading): self
    {
        $this->trading = $trading;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


}
