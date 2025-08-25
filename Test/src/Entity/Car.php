<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CarRepository::class)]
#[Vich\Uploadable()]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['rides.show'])]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    private ?string $model = null;

    #[ORM\Column(length: 255)]
    private ?string $liscencePlate = null;

    #[ORM\Column(nullable: true)]
    private ?bool $animal = null;

    #[ORM\Column(nullable: true)]
    private ?bool $smoke = null;

    #[ORM\Column(length: 255)]
    private ?string $energy = null;

    /**
     * @var Collection<int, Ride>
     */
    #[ORM\OneToMany(targetEntity: Ride::class, mappedBy: 'car', /*cascade: ['remove']*/)]
    private Collection $rides;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    #[Vich\UploadableField(mapping: 'cars', fileNameProperty: 'thumbnail')]
    #[Assert\Image()]
    private ?File $thumbnailFile = null;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $driver = null;

    public function __construct()
    {
        $this->rides = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getLiscencePlate(): ?string
    {
        return $this->liscencePlate;
    }

    public function setLiscencePlate(string $liscencePlate): static
    {
        $this->liscencePlate = $liscencePlate;

        return $this;
    }

    public function isAnimal(): ?bool
    {
        return $this->animal;
    }

    public function setAnimal(?bool $animal): static
    {
        $this->animal = $animal;

        return $this;
    }

    public function isSmoke(): ?bool
    {
        return $this->smoke;
    }

    public function setSmoke(?bool $smoke): static
    {
        $this->smoke = $smoke;

        return $this;
    }

    public function getEnergy(): ?string
    {
        return $this->energy;
    }

    public function setEnergy(string $energy): static
    {
        $this->energy = $energy;

        return $this;
    }

    /**
     * @return Collection<int, Ride>
     */
    public function getRides(): Collection
    {
        return $this->rides;
    }

    public function addRide(Ride $ride): static
    {
        if (!$this->rides->contains($ride)) {
            $this->rides->add($ride);
            $ride->setCar($this);
        }

        return $this;
    }

    public function removeRide(Ride $ride): static
    {
        if ($this->rides->removeElement($ride)) {
            // set the owning side to null (unless already changed)
            if ($ride->getCar() === $this) {
                $ride->setCar(null);
            }
        }

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getThumbnailFile(): ?File
    {
        return $this->thumbnailFile;
    }

    public function setThumbnailFile(?File $thumbnailFile): static
    {
        $this->thumbnailFile = $thumbnailFile;

        return $this;
    }

    public function getDriver(): ?User
    {
        return $this->driver;
    }

    public function setDriver(?User $driver): static
    {
        $this->driver = $driver;

        return $this;
    }
}
