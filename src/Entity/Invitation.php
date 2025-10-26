<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Invitation
 *
 * @ORM\Table(name="invitation", indexes={@ORM\Index(name="fk_invit_user", columns={"idjoueur"}), @ORM\Index(name="fk_invit_captain", columns={"idcaptain"})})
 * @ORM\Entity
 */
class Invitation
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateInvit", type="date", nullable=false)
     */
    private $dateinvit;

    /**
     * @var \Joueur
     *
     * @ORM\ManyToOne(targetEntity="Joueur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idjoueur", referencedColumnName="id")
     * })
     */
    private $idjoueur;

    /**
     * @var \Joueur
     *
     * @ORM\ManyToOne(targetEntity="Joueur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idcaptain", referencedColumnName="id")
     * })
     */
    private $idcaptain;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateinvit(): ?\DateTimeInterface
    {
        return $this->dateinvit;
    }

    public function setDateinvit(\DateTimeInterface $dateinvit): self
    {
        $this->dateinvit = $dateinvit;

        return $this;
    }

    public function getIdjoueur(): ?Joueur
    {
        return $this->idjoueur;
    }

    public function setIdjoueur(?Joueur $idjoueur): self
    {
        $this->idjoueur = $idjoueur;

        return $this;
    }

    public function getIdcaptain(): ?Joueur
    {
        return $this->idcaptain;
    }

    public function setIdcaptain(?Joueur $idcaptain): self
    {
        $this->idcaptain = $idcaptain;

        return $this;
    }


}
