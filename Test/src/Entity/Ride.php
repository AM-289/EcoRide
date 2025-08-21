<?php

namespace App\Entity;

use App\Repository\RideRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;

#[ORM\Entity(repositoryClass: RideRepository::class)]
class Ride
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Departure = null;

    #[ORM\Column(length: 255)]
    private ?string $Arrival = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $DepartureDate = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $ArrivalDate = null;

    #[ORM\Column(length: 255)]
    private ?string $Driver = null;

    #[ORM\Column(length: 255)]
    private ?string $Car = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeparture(): ?string
    {
        return $this->Departure;
    }

    public function setDeparture(string $Departure): static
    {
        $this->Departure = $Departure;

        return $this;
    }

    public function getArrival(): ?string
    {
        return $this->Arrival;
    }

    public function setArrival(string $Arrival): static
    {
        $this->Arrival = $Arrival;

        return $this;
    }

    public function getDepartureDate(): ?\DateTimeImmutable
    {
        return $this->DepartureDate;
    }

    public function setDepartureDate(\DateTimeImmutable $DepartureDate): static
    {
        $this->DepartureDate = $DepartureDate;

        return $this;
    }

    public function getArrivalDate(): ?\DateTimeImmutable
    {
        return $this->ArrivalDate;
    }

    public function setArrivalDate(\DateTimeImmutable $ArrivalDate): static
    {
        $this->ArrivalDate = $ArrivalDate;

        return $this;
    }

    public function getDriver(): ?string
    {
        return $this->Driver;
    }

    public function setDriver(string $Driver): static
    {
        $this->Driver = $Driver;

        return $this;
    }

    public function getCar(): ?string
    {
        return $this->Car;
    }

    public function setCar(string $Car): static
    {
        $this->Car = $Car;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
