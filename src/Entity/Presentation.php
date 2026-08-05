<?php

namespace App\Entity;

use App\Repository\PresentationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PresentationRepository::class)]
class Presentation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'presentations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le titre est obligatoire.')]
    #[Assert\Length(max: 255, maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères.')]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageFilename = null;

    #[ORM\Column(length: 7)]
    #[Assert\Regex(
       pattern: '/^#[0-9a-fA-F]{6}$/',
       message: 'La couleur doit être au format hexadécimal (ex: #1a1a1a).'
     )]
    private ?string $backgroundColor = null;

    #[ORM\Column(length: 20)]
    #[Assert\Choice(choices: ['1:1', '16:9', '4:3'], message: 'Format non supporté.')]
    private ?string $format = null;

    #[ORM\Column]
    private ?float $posX = null;

    #[ORM\Column]
    private ?float $posY = null;

    #[ORM\Column]
    #[Assert\Range(min: 0.1, max: 5.0, notInRangeMessage: 'L\'échelle doit être entre {{ min }} et {{ max }}.')]
    private ?float $scale = null;

    #[ORM\Column]
    #[Assert\Range(min: 0, max: 100, notInRangeMessage: 'La bordure doit être entre {{ min }} et {{ max }}.')]
    private ?int $border = null;

    #[ORM\Column(length: 7)]
    #[Assert\Regex(
       pattern: '/^#[0-9a-fA-F]{6}$/',
       message: 'La couleur de la bordure doit être au format hexadécimal (ex: #1a1a1a).'
     )]
    private ?string $borderColor = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
{
    $this->createdAt = new \DateTimeImmutable();
    $this->title = "Ma presentation";
    $this->backgroundColor = null;
    $this->posX = 100.0;
    $this->posY = 50.0;
    $this->scale = 1.0;
    $this->border = 0;
}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(?string $imageFilename): static
    {
        $this->imageFilename = $imageFilename;

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(string $backgroundColor): static
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function getPosX(): ?float
    {
        return $this->posX;
    }

    public function setPosX(float $posX): static
    {
        $this->posX = $posX;

        return $this;
    }

    public function getPosY(): ?float
    {
        return $this->posY;
    }

    public function setPosY(float $posY): static
    {
        $this->posY = $posY;

        return $this;
    }

    public function getScale(): ?float
    {
        return $this->scale;
    }

    public function setScale(float $scale): static
    {
        $this->scale = $scale;

        return $this;
    }

    public function getBorder(): ?int
    {
        return $this->border;
    }

    public function setBorder(int $border): static
    {
        $this->border = $border;

        return $this;
    }

    public function getBorderColor(): ?string
    {
        return $this->borderColor;
    }

    public function setBorderColor(string $borderColor): static
    {
        $this->borderColor = $borderColor;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
