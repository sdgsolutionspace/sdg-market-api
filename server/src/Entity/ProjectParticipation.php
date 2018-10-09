<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectParticipation
 *
 * @ORM\Table(name="project_participation", indexes={@ORM\Index(name="fk_project_participation_git_project1_idx", columns={"git_project_id"})})
 * @ORM\Entity
 */
class ProjectParticipation
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
     * @ORM\Column(name="number_of_tokens", type="decimal", precision=8, scale=2, nullable=false)
     */
    private $numberOfTokens;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="calculation_utc_datetime", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $calculationUtcDatetime = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="commit_id", type="string", length=45, nullable=false)
     */
    private $commitId;

    /**
     * @var GitProject
     *
     * @ORM\ManyToOne(targetEntity="GitProject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="git_project_id", referencedColumnName="id")
     * })
     */
    private $gitProject;

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

    public function getCalculationUtcDatetime(): ?\DateTimeInterface
    {
        return $this->calculationUtcDatetime;
    }

    public function setCalculationUtcDatetime(\DateTimeInterface $calculationUtcDatetime): self
    {
        $this->calculationUtcDatetime = $calculationUtcDatetime;

        return $this;
    }

    public function getCommitId(): ?string
    {
        return $this->commitId;
    }

    public function setCommitId(string $commitId): self
    {
        $this->commitId = $commitId;

        return $this;
    }

    public function getGitProject(): ?GitProject
    {
        return $this->gitProject;
    }

    public function setGitProject(?GitProject $gitProject): self
    {
        $this->gitProject = $gitProject;

        return $this;
    }


}
