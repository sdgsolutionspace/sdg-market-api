<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProjectParticipation.
 *
 * @ORM\Table(name="project_participation", indexes={@ORM\Index(name="fk_project_participation_git_project1_idx", columns={"git_project_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\ProjectParticipationRepository")
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
     * @var float
     *
     * @ORM\Column(name="number_of_tokens", type="decimal", precision=8, scale=2, nullable=false)
     * @Assert\Type("numeric")
     * @Assert\NotBlank()
     */
    private $numberOfTokens;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="calculation_utc_datetime", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     * @Assert\DateTime()
     */
    private $calculationUtcDatetime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="commit_date", type="datetime", nullable=false)
     * @Assert\DateTime()
     */
    private $commitDate;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="commit_id", type="string", length=45, nullable=false)
     */
    private $commitId;

    /**
     * @var GitProject
     *
     * @ORM\ManyToOne(targetEntity="GitProject", inversedBy="participtions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="git_project_id", referencedColumnName="id")
     * })
     *
     * @Assert\NotBlank()
     */
    private $gitProject;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $user;

    /**
     * @var string|null
     *
     * @ORM\Column(name="committer_email", type="string", length=255, nullable=true)
     */
    private $committerEmail;

    /**
     * @var string|null
     *
     * @ORM\Column(name="committer_username", type="string", length=255, nullable=true)
     */
    private $committerUsername;

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

    public function getCalculationUtcDatetime(): ? \DateTimeInterface
    {
        return $this->calculationUtcDatetime;
    }

    public function setCalculationUtcDatetime($calculationUtcDatetime): self
    {
        $this->calculationUtcDatetime = $calculationUtcDatetime;

        return $this;
    }

    public function getCommitId(): ? string
    {
        return $this->commitId;
    }

    public function setCommitId(string $commitId): self
    {
        $this->commitId = $commitId;

        return $this;
    }

    public function getGitProject(): ? GitProject
    {
        return $this->gitProject;
    }

    public function setGitProject(? GitProject $gitProject): self
    {
        $this->gitProject = $gitProject;

        return $this;
    }

    /**
     * Get the value of commitDate.
     *
     * @return \DateTime
     */
    public function getCommitDate(): ? DateTime
    {
        return $this->commitDate;
    }

    /**
     * Set the value of commitDate.
     *
     * @param \DateTime $commitDate
     *
     * @return self
     */
    public function setCommitDate($commitDate): self
    {
        $this->commitDate = $commitDate;

        return $this;
    }

    /**
     * Get the value of user.
     *
     * @return User
     */
    public function getUser(): ? User
    {
        return $this->user;
    }

    /**
     * Set the value of user.
     *
     * @param User $user
     *
     * @return self
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of committerEmail.
     *
     * @return string|null
     */
    public function getCommitterEmail()
    {
        return $this->committerEmail;
    }

    /**
     * Set the value of committerEmail.
     *
     * @param string|null $committerEmail
     *
     * @return self
     */
    public function setCommitterEmail($committerEmail)
    {
        $this->committerEmail = $committerEmail;

        return $this;
    }

    /**
     * Get the value of committerUsername.
     *
     * @return string|null
     */
    public function getCommitterUsername()
    {
        return $this->committerUsername;
    }

    /**
     * Set the value of committerUsername.
     *
     * @param string|null $committerUsername
     *
     * @return self
     */
    public function setCommitterUsername($committerUsername)
    {
        $this->committerUsername = $committerUsername;

        return $this;
    }
}
