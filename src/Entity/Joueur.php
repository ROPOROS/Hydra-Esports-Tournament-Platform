<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Joueur
 *
 * @ORM\Table(name="joueur")
 * @ORM\Entity(repositoryClass="App\Repository\JoueurRepository")
 * @UniqueEntity(
 *  fields={"mail"},
 *     message="Ce mail existe déja"
 *)
 *
 */
class Joueur implements UserInterface
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
     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="Veuillez remplir ce champs")
     * @Assert\Type("string")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="Veuillez remplir ce champs")
     */
    private $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="date", nullable=false)
     *
     */
    private $datenaissance;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mail", type="string", length=150, nullable=true)
     * @Assert\NotBlank(message="Veuillez remplir ce champs")
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="mdp", type="string", length=256, nullable=false)
     * @Assert\NotBlank(message="Veuillez remplir ce champs")
     * * @Assert\Length(
     *      min = 5,
     *
     *      minMessage = "Votre mot de passe doit contenir minimum {{ limit }} characteres"
     *
     * )
     *
     *
     *
     */
    private $mdp;

    /**
     * @var float
     *
     * @ORM\Column(name="wallet", type="float", precision=10, scale=0, nullable=false)
     */
    private $wallet;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=0, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=200, nullable=false)
     */
    private $photo;

    /**
     * @var int
     *
     * @ORM\Column(name="avertissement", type="integer", nullable=false)
     */
    private $avertissement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDatenaissance(): ?\DateTimeInterface
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(\DateTimeInterface $datenaissance): self
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->mail;
    }

    public function setUsername(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->mdp;
    }

    public function setPassword(string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getWallet(): ?float
    {
        return $this->wallet;
    }

    public function setWallet(float $wallet): self
    {
        $this->wallet = $wallet;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getAvertissement(): ?int
    {
        return $this->avertissement;
    }

    public function setAvertissement(int $avertissement): self
    {
        $this->avertissement = $avertissement;

        return $this;
    }
    public  function eraseCredentials()
    {
    }
    public function getSalt()
    {
    }
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function __toString(){
        return $this->id."";
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }


}
