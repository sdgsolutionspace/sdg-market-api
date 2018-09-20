<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GitProject
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
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="git_address", type="string", length=120, nullable=false)
     */
    private $gitAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="project_address", type="string", length=120, nullable=false)
     */
    private $projectAddress;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGitAddress(): ?string
    {
        return $this->gitAddress;
    }

    public function setGitAddress(string $gitAddress): self
    {
        $this->gitAddress = $gitAddress;

        return $this;
    }

    public function getProjectAddress(): ?string
    {
        return $this->projectAddress;
    }

    public function setProjectAddress(string $projectAddress): self
    {
        $this->projectAddress = $projectAddress;

        return $this;
    }


}
