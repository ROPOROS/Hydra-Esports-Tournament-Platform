<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\PariRepository;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Pari
 *
 * @ORM\Table(name="pari")
 * @ORM\Entity(repositoryClass=PariRepository::class)
 */
class Pari
{

    /**
     * @var int
     * @ORM\Id
     * @Groups("Parigroup")
     * @ORM\ManyToOne(targetEntity="Tmatchs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idMatch", referencedColumnName="id")
     * })
     */
    private $idmatch;

    /**
     * @var int
     * @ORM\Id
     * @Groups("Parigroup")
     * @ORM\ManyToOne(targetEntity="Joueur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUser", referencedColumnName="id")
     * })

     */
    private $iduser;

    /**
     * @var int
     *
     * @ORM\Column(name="montant", type="integer", nullable=false)
     * @Groups("Parigroup")
     * @Assert\NotBlank
     */
    private $montant;

    /**
     * @var string
     *
     * @ORM\Column(name="idEquipe", type="string", length=3, nullable=false)
     * @Groups("Parigroup")
     */
    private $idequipe;


    public function getIdmatch(): ?Tmatchs
    {
        return $this->idmatch;
    }

    public function getIduser(): ?Joueur
    {
        return $this->iduser;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getIdequipe(): ?string
    {
        return $this->idequipe;
    }

    public function setIdequipe(string $idequipe): self
    {
        $this->idequipe = $idequipe;

        return $this;
    }

    public function setIduser(?Joueur $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function setIdmatch(?Tmatchs $idmatch): self
    {
        $this->idmatch = $idmatch;

        return $this;
    }



}
