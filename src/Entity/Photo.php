<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\PhotoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
    ],
    normalizationContext: [
        'groups' => ['photo:read']
    ]
)]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('photo:read')]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Rover $rover = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Camera $camera = null;

    #[ORM\Column(length: 255)]
    #[Groups(['photo:read'])]
    private ?string $imgSrc = null;

    public function __construct(\DateTimeInterface $date, Rover $rover, Camera $camera, string $imgSrc)
    {
        $this->date = $date;
        $this->rover = $rover;
        $this->camera = $camera;
        $this->imgSrc = $imgSrc;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups('photo:read')]
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    #[Groups('photo:read')]
    public function getDateString(): string
    {
        return $this->date->format('Y-m-d');
    }


    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getRover(): ?Rover
    {
        return $this->rover;
    }

    public function setRover(?Rover $rover): static
    {
        $this->rover = $rover;

        return $this;
    }

    public function getCamera(): ?Camera
    {
        return $this->camera;
    }

    public function setCamera(?Camera $camera): static
    {
        $this->camera = $camera;

        return $this;
    }

    public function getImgSrc(): ?string
    {
        return $this->imgSrc;
    }

    public function setImgSrc(string $imgSrc): static
    {
        $this->imgSrc = $imgSrc;

        return $this;
    }
}
