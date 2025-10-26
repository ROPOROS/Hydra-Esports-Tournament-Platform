<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\TmatchsRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Tmatchs
 *
 * @ORM\Table(name="tmatchs", indexes={@ORM\Index(name="idEquipeA", columns={"idEquipeA"}),    @ORM\Index(name="idEquipeB", columns={"idEquipeB"}), @ORM\Index(name="idTournoi", columns={"idTournoi"})})
 * @ORM\Entity(repositoryClass=TmatchsRepository::class)
 */

class Tmatchs
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("Tmatchsgroup")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=0, nullable=false)
     * @Groups("Tmatchsgroup")
     */
    private $etat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateMatch", type="date", nullable=false)
     * @Assert\NotBlank
     * @Groups("Tmatchsgroup")
     */
    private $datematch;

    /**
     * @var string
     *
     * @ORM\Column(name="score", type="string", length=0, nullable=false)
     * @Groups("Tmatchsgroup")
     */
    private $score;

    /**
     * @var int
     *
     * @ORM\Column(name="heureMatch", type="integer", nullable=false)
     * @Assert\NotBlank
     * @Groups("Tmatchsgroup")
     */
    private $heurematch;

    /**
     * @var int
     *
     * @ORM\Column(name="phase", type="integer", nullable=false)
     * @Groups("Tmatchsgroup")
     */
    private $phase;

    /**
     * @var \Tournoi
     *
     * @ORM\ManyToOne(targetEntity="Tournoi")
     * @Groups("Tmatchsgroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTournoi", referencedColumnName="id")
     * })
     */
    private $idtournoi;

    /**
     * @var \Team
     *
     * @ORM\ManyToOne(targetEntity="Team")
     * @Groups("Tmatchsgroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idEquipeB", referencedColumnName="ID")
     * })
     */
    private $idequipeb;

    /**
     * @var \Team
     *
     * @ORM\ManyToOne(targetEntity="Team")
     * @Groups("Tmatchsgroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idEquipeA", referencedColumnName="ID")
     * })
     */
    private $idequipea;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->iduser = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /*public function __toString() {
        return ($this->id)."";
    }*/

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDatematch(): ?\DateTimeInterface
    {
        return $this->datematch;
    }

    public function setDatematch(\DateTimeInterface $datematch): self
    {
        $this->datematch = $datematch;

        return $this;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(string $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getHeurematch(): ?int
    {
        return $this->heurematch;
    }

    public function setHeurematch(int $heurematch): self
    {
        $this->heurematch = $heurematch;

        return $this;
    }

    public function getPhase(): ?int
    {
        return $this->phase;
    }

    public function setPhase(int $phase): self
    {
        $this->phase = $phase;

        return $this;
    }

    public function getIdtournoi(): ?Tournoi
    {
        return $this->idtournoi;
    }

    public function setIdtournoi(?Tournoi $idtournoi): self
    {
        $this->idtournoi = $idtournoi;

        return $this;
    }

    public function getIdequipeb(): ?Team
    {
        return $this->idequipeb;
    }

    public function setIdequipeb(?Team $idequipeb): self
    {
        $this->idequipeb = $idequipeb;

        return $this;
    }

    public function getIdequipea(): ?Team
    {
        return $this->idequipea;
    }

    public function setIdequipea(?Team $idequipea): self
    {
        $this->idequipea = $idequipea;

        return $this;
    }


}
