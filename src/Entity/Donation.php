<?php

namespace App\Entity;

use App\Entity\Joueur;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;



/**
 * Donation
 *
 * @ORM\Table(name="donation")
 * @ORM\Entity
 */
class Donation
{

    /**
     * @var int
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="montant", type="integer", nullable=false)
     *
     */
    private $montant;

    /**
     * @var int
     *
     * @ORM\Column(name="idUser", type="integer", nullable=false)
     */
    private $iduser;

    /**
     * @var int
     *
     * @ORM\Column(name="idTeam", type="integer", nullable=false)
     */
    private $idteam;

    /**
     * @var string A "Y-m-d" formatted value
     *
     * @ORM\Column(name="dateDon", type="date", nullable=false)
     */
    private $datedon;

    private $user;
    private $team;
    public function setUser(Joueur $user): self
    {
        $this->user = $user;

        return $this;
    }
    public function getUser(): ?Joueur
    {
        return $this->user;
    }
    public function getTeam(): ?Team
    {
        return $this->team;
    }
    public function setTeam(Team $t): self
    {
        $this->team = $t;

        return $this;
    }

    public static function loadValidatorMontant(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('montant', new Assert\GreaterThan(0));
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    public function setIduser(Joueur $iduser): self
    {
        $this->iduser = $iduser->getId();

        return $this;
    }

    public function getIdteam(): ?int
    {
        return $this->idteam;
    }

    public function setIdteam(Team $idteam): self
    {
        $this->idteam = $idteam->getId();

        return $this;
    }

    public function getDatedon(): ?\DateTimeInterface
    {
        return $this->datedon;
    }

    public function setDatedon(\DateTimeInterface $datedon): self
    {
        $this->datedon = $datedon;

        return $this;
    }


}
