<?php

namespace App\Entity;

use App\Repository\RoverRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RoverRepository::class)]
class Rover
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('photo:read')]
    private ?int $id = null;

    #[Groups('photo:read')]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'rover', targetEntity: Photo::class, orphanRemoval: true)]
    private Collection $photos;

    #[ORM\ManyToMany(targetEntity: Camera::class, inversedBy: 'rovers')]
    private Collection $Camera;

    #[Groups('photo:read')]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $min_date = null;

    #[Groups('photo:read')]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $max_date = null;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->Camera = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setRover($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getRover() === $this) {
                $photo->setRover(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Camera>
     */
    public function getCamera(): Collection
    {
        return $this->Camera;
    }

    public function addCamera(Camera $camera): static
    {
        if (!$this->Camera->contains($camera)) {
            $this->Camera->add($camera);
        }

        return $this;
    }

    public function removeCamera(Camera $camera): static
    {
        $this->Camera->removeElement($camera);

        return $this;
    }

    public function getMinDate(): ?\DateTimeInterface
    {
        return $this->min_date;
    }

    public function setMinDate(\DateTimeInterface $min_date): static
    {
        $this->min_date = $min_date;

        return $this;
    }

    public function getMaxDate(): ?\DateTimeInterface
    {
        return $this->max_date;
    }

    public function setMaxDate(\DateTimeInterface $max_date): static
    {
        $this->max_date = $max_date;

        return $this;
    }
}
