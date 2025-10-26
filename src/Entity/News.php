<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * News
 *
 * @ORM\Table(name="news", indexes={@ORM\Index(name="idJeu", columns={"idJeu"})})
 * @ORM\Entity(repositoryClass="App\Repository\NewsRepository")
 * @Vich\Uploadable
 */
class News
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
     *
     * @ORM\Column(name="sujet_n", type="string", length=256, nullable=false)
     * @Assert\NotBlank

     * @Groups("post:read")
     */
    private $sujetN;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", length=65535, nullable=false)
     * @Assert\NotBlank
     *  @Assert\Regex(
     *     pattern     = "/^[a-zA-Z]+[a-zA-Z]+/",
     * message = " please enter your description correctly .")
     * @Groups("post:read")
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=256, nullable=false)
     * @Groups("post:read")
     */
    private $image;

    /**
     * @var \DateTime


     * @ORM\Column(name="date_c", type="date", nullable=false)
     * @Groups("post:read")
     */
    private $dateC;

    /**
     * @var \DateTime


     * @ORM\Column(name="date_f", type="date", nullable=false)
     * @Groups("post:read")
     */
    private $dateF;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     * @Groups("post:read")
     * @var File
     */
    private $imageFile;

    /**
     * @var \Jeu
     *
     * @ORM\ManyToOne(targetEntity="Jeu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idJeu", referencedColumnName="id")
     * })
     * @Groups("post:read")
     */
    private $idjeu;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSujetN(): ?string
    {
        return $this->sujetN;
    }

    public function setSujetN(string $sujetN): self
    {
        $this->sujetN = $sujetN;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDateC(): ?\DateTimeInterface
    {
        return $this->dateC;
    }

    public function setDateC(\DateTimeInterface $dateC): self
    {
        $this->dateC = $dateC;

        return $this;
    }

    public function getDateF(): ?\DateTimeInterface
    {
        return $this->dateF;
    }

    public function setDateF(\DateTimeInterface $dateF): self
    {
        $this->dateF = $dateF;

        return $this;
    }

    public function getIdjeu(): ?Jeu
    {
        return $this->idjeu;
    }

    public function setIdjeu(?Jeu $idjeu): self
    {
        $this->idjeu = $idjeu;

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
