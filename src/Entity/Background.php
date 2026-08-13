<?php

namespace App\Entity;

use App\Repository\BackgroundRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: BackgroundRepository::class)]
class Background
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Presentation>
     */
    #[ORM\OneToMany(targetEntity: Presentation::class, mappedBy: 'background')]
    private Collection $presentations;

    #[ORM\Column(length: 20, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['solid', 'degrade', 'image'], message: 'Type de fond non supporté.')]
    private ?string $type = null;

    #[ORM\Column(length: 7, nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Regex(
        pattern: '/^#[0-9a-fA-F]{6}$/',
        message: 'La couleur doit être au format hexadécimal (ex: #1a1a1a).'
    )]
    private ?string $color1 = null;

    #[ORM\Column(length: 7, nullable: true)]
    #[Assert\Regex(
        pattern: '/^#[0-9a-fA-F]{6}$/',
        message: 'La couleur doit être au format hexadécimal (ex: #1a1a1a).'
    )]
    private ?string $color2 = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: 0, max: 360, notInRangeMessage: 'L\'angle du dégradé doit être entre {{ min }} et {{ max }}.')]
    private ?float $gradientAngle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageFilename = null;

    public function __construct()
    {
        $this->presentations = new ArrayCollection();
        $this->type = 'solid';
        $this->color1 = '#1a1a1a';
        $this->color2 = null;
        $this->gradientAngle = null;
        $this->imageFilename = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Presentation>
     */
    public function getPresentation(): Collection
    {
        return $this->presentations;
    }

    public function addPresentation(Presentation $presentation): static
    {
        if (!$this->presentations->contains($presentation)) {
            $this->presentations->add($presentation);
            $presentation->setBackground($this);
        }

        return $this;
    }

    public function removePresentation(Presentation $presentation): static
    {
        if ($this->presentations->removeElement($presentation)) {
            // set the owning side to null (unless already changed)
            if ($presentation->getBackground() === $this) {
                $presentation->setBackground(null);
            }
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getColor1(): ?string
    {
        return $this->color1;
    }

    public function setColor1(string $color1): static
    {
        $this->color1 = $color1;

        return $this;
    }

    public function getColor2(): ?string
    {
        return $this->color2;
    }

    public function setColor2(?string $color2): static
    {
        $this->color2 = $color2;

        return $this;
    }

    public function getGradiantAngle(): ?float
    {
        return $this->gradientAngle;
    }

    public function setGradiantAngle(?float $gradiantAngle): static
    {
        $this->gradientAngle = $gradiantAngle;

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
}
