<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * GitProject.
 *
 * @ORM\Table(name="git_project")
 * @ORM\Entity
 */
class GitProject
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="git_address", type="string", length=120, nullable=false)
     * @Assert\NotBlank()
     */
    private $gitAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="project_address", type="string", length=120, nullable=false)
     * @Assert\NotBlank()
     */
    private $projectAddress;

    /**
     * @var int
     *
     * @ORM\Column(name="project_value", type="integer", nullable=false, options={"default"=1000})
     */
    private $projectValue = 1000;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", nullable=false, options={"default"=FALSE})
     * @Assert\NotNull()
     */
    private $active = 1;

    public function getId(): ? int
    {
        return $this->id;
    }

    public function isActive(): ? bool
    {
        return $this->active;
    }

    public function setActive($active): ? bool
    {
        return $this->active = (bool) $active;
    }

    public function getName(): ? string
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGitAddress(): ? string
    {
        return $this->gitAddress;
    }

    public function setGitAddress($gitAddress): self
    {
        $this->gitAddress = $gitAddress;

        return $this;
    }

    public function getProjectAddress(): ? string
    {
        return $this->projectAddress;
    }

    public function setProjectAddress($projectAddress): self
    {
        $this->projectAddress = $projectAddress;

        return $this;
    }

    /**
     * Get the value of projectValue.
     *
     * @return int
     */
    public function getProjectValue(): int
    {
        return $this->projectValue;
    }

    /**
     * Set the value of projectValue.
     *
     * @param int $projectValue
     *
     * @return self
     */
    public function setProjectValue(int $projectValue): self
    {
        $this->projectValue = $projectValue;

        return $this;
    }

    public function incrementProjectValue(int $nbTokens): self
    {
        $this->projectValue += $nbTokens;

        return $this;
    }
}
