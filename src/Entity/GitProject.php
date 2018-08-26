<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GitProject
 *
 * @ORM\Table(name="git_project", indexes={@ORM\Index(name="fk_project_participation1_idx", columns={"participation_id"})})
 * @ORM\Entity
 */
class GitProject
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

    /**
     * @var \ProjectParticipation
     *
     * @ORM\ManyToOne(targetEntity="ProjectParticipation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="participation_id", referencedColumnName="id")
     * })
     */
    private $participation;

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getGitAddress()
    {
        return $this->gitAddress;
    }

    /**
     * @param string $gitAddress
     */
    public function setGitAddress($gitAddress)
    {
        $this->gitAddress = $gitAddress;
    }

    /**
     * @return string
     */
    public function getProjectAddress()
    {
        return $this->projectAddress;
    }

    /**
     * @param string $projectAddress
     */
    public function setProjectAddress($projectAddress)
    {
        $this->projectAddress = $projectAddress;
    }

    /**
     * @return \ProjectParticipation
     */
    public function getParticipation()
    {
        return $this->participation;
    }

    /**
     * @param \ProjectParticipation $participation
     */
    public function setParticipation($participation)
    {
        $this->participation = $participation;
    }


}
