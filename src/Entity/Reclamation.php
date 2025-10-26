<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation")
 * @ORM\Entity(repositoryClass="App\Repository\ReclamationRepository")
 * @Vich\Uploadable
 */
class Reclamation
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
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="sujet", type="string", length=256, nullable=false)
     *  @Assert\Regex(
     *     pattern     = "/^[a-zA-Z]+[a-zA-Z]+/",
     * message = " please enter your subject correctly .")
     * @Groups("ReclamationGroup")
     */
    private $sujet;

    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     *  @Assert\Regex(
     *     pattern     = "/^[a-zA-Z]+[a-zA-Z]+/",
     * message = " please enter your description correctly .")
     * @Groups("ReclamationGroup")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="attachement", type="string", length=256, nullable=false)
     * @Groups("post:read")
     */
    private $attachement;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=256, nullable=false)
     * @Assert\NotBlank
     * @Assert\Email(
     * message = "{{ value }} is not a valid email.")
     * @Groups("ReclamationGroup")
     */
    private $email;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_tel", type="integer", nullable=false)
     * @Assert\NotBlank
     * @Assert\PositiveOrZero
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = " Numero doit être au moins {{ limit }} characters long",
     *      maxMessage = " Numero ne peut pas dépasser {{ limit }} characters"
     * )
     * @Assert\NotBlank
     * @Groups("ReclamationGroup")
     */
    private $numeroTel;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=256, nullable=false)
     * @Groups("ReclamationGroup")
     */
    private $status;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="attachement")
     * @var File
     * @Groups("ReclamationGroup")
     */
    private $imageFile;

    /**
     * @var string
     *
     * @ORM\Column(name="object", type="string", length=256, nullable=false)
     * @Assert\Choice({"Thechnical issue","Report a player","Other"})
     * @Groups("ReclamationGroup")
     */
    private $object;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAttachement(): ?string
    {
        return $this->attachement;
    }

    public function setAttachement(?string $attachement): self
    {
        $this->attachement = $attachement;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNumeroTel(): ?int
    {
        return $this->numeroTel;
    }

    public function setNumeroTel(int $numeroTel): self
    {
        $this->numeroTel = $numeroTel;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function setObject(string $object): self
    {
        $this->object = $object;

        return $this;
    }

    public function setImageFile($image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }


}
