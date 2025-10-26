<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Match
 *
 * @ORM\Table(name="match")
 * @ORM\Entity(repositoryClass="App\Repository\MatchRepository")
 */

class Matchs
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
     * @var int
     *
     * @ORM\Column(name="idTournoi", type="integer", nullable=false)
     */
    private $idtournoi;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=0, nullable=false)
     */
    private $etat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateMatch", type="date", nullable=false)
     */
    private $datematch;

    /**
     * @var string
     *
     * @ORM\Column(name="score", type="string", length=0, nullable=false)
     */
    private $score;

    /**
     * @var int
     *
     * @ORM\Column(name="heureMatch", type="integer", nullable=false)
     */
    private $heurematch;

    /**
     * @var int
     *
     * @ORM\Column(name="idEquipeA", type="integer", nullable=false)
     */
    private $idequipea;

    /**
     * @var int
     *
     * @ORM\Column(name="idEquipeB", type="integer", nullable=false)
     */
    private $idequipeb;

    /**
     * @var int
     *
     * @ORM\Column(name="phase", type="integer", nullable=false)
     */
    private $phase;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdtournoi(): ?int
    {
        return $this->idtournoi;
    }

    public function setIdtournoi(int $idtournoi): self
    {
        $this->idtournoi = $idtournoi;

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

    public function getIdequipea(): ?int
    {
        return $this->idequipea;
    }

    public function setIdequipea(int $idequipea): self
    {
        $this->idequipea = $idequipea;

        return $this;
    }

    public function getIdequipeb(): ?int
    {
        return $this->idequipeb;
    }

    public function setIdequipeb(int $idequipeb): self
    {
        $this->idequipeb = $idequipeb;

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


}
