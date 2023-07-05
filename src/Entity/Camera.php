<?php

namespace App\Entity;

use App\Repository\CameraRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CameraRepository::class)]
class Camera
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('photo:read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('photo:read')]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'camera', targetEntity: Photo::class, orphanRemoval: true)]
    private Collection $photos;

    #[ORM\ManyToMany(targetEntity: Rover::class, mappedBy: 'Camera')]
    private Collection $rovers;

    #[ORM\Column(length: 255)]
    #[Groups('photo:read')]
    private ?string $full_name = null;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->rovers = new ArrayCollection();
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
            $photo->setCamera($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getCamera() === $this) {
                $photo->setCamera(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rover>
     */
    public function getRovers(): Collection
    {
        return $this->rovers;
    }

    public function addRover(Rover $rover): static
    {
        if (!$this->rovers->contains($rover)) {
            $this->rovers->add($rover);
            $rover->addCamera($this);
        }

        return $this;
    }

    public function removeRover(Rover $rover): static
    {
        if ($this->rovers->removeElement($rover)) {
            $rover->removeCamera($this);
        }

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): static
    {
        $this->full_name = $full_name;

        return $this;
    }
}
