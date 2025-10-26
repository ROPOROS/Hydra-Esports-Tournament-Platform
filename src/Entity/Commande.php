<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="fk_product_commande", columns={"idProduit"}), @ORM\Index(name="fk_user_commande", columns={"idUser"})})
 * @ORM\Entity(repositoryClass="App\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCommande", type="date", nullable=false)
     * @Groups("post:read")
     */
    private $datecommande;

    /**
     * @var int
     *
     * @ORM\Column(name="confirme", type="integer", nullable=false)
     * @Groups("post:read")
     */
    private $confirme = '0';

    /**
     * @var \Joueur
     *
     * @ORM\ManyToOne(targetEntity="Joueur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUser", referencedColumnName="id")
     * })
     * @Groups("post:read")
     */
    private $iduser;

    /**
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idProduit", referencedColumnName="id")
     * })
     * @Groups("post:read")
     */
    private $idproduit;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatecommande(): ?\DateTimeInterface
    {
        return $this->datecommande;
    }

    public function setDatecommande(\DateTimeInterface $datecommande): self
    {
        $this->datecommande = $datecommande;

        return $this;
    }

    public function getConfirme(): ?int
    {
        return $this->confirme;
    }

    public function setConfirme(int $confirme): self
    {
        $this->confirme = $confirme;

        return $this;
    }

    public function getIduser(): ?Joueur
    {
        return $this->iduser;
    }

    public function setIduser(?Joueur $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIdproduit(): ?Produit
    {
        return $this->idproduit;
    }

    public function setIdproduit(?Produit $idproduit): self
    {
        $this->idproduit = $idproduit;

        return $this;
    }

    public function __toString(): string
    {
        return String().$this->getId();
    }

}
