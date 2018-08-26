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


}
