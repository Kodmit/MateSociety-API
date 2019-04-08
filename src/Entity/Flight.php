<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={"access_control"="is_granted('ROLE_USER')"},
 *     collectionOperations={
 *         "get",
 *         "post"={"access_control"="is_granted('ROLE_WRITER')"}
 *     },
 *     itemOperations={
 *          "get",
 *          "put"={"access_control"="is_granted('ROLE_WRITER')"},
 *          "delete"={"access_control"="is_granted('ROLE_WRITER')"}
 *     },
 *     normalizationContext={"groups"={"read_flight"}},
 *     denormalizationContext={"groups"={"write_flight"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\FlightRepository")
 */
class Flight
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 2,
     *      max = 100,
     * )
     * @Groups({"read_flight", "write_flight", "read_airport", "read_gate", "read_passenger", "read_company", "read_plane"})
     */
    private $departure;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 2,
     *      max = 100,
     * )
     * @Groups({"read_flight", "write_flight", "read_airport", "read_gate", "read_passenger", "read_company", "read_plane"})
     */
    private $arrival;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     * @Groups({"read_flight", "write_flight", "read_airport", "read_gate", "read_passenger", "read_plane"})
     */
    private $departure_time;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     * @Groups({"read_flight", "write_flight", "read_airport", "read_gate", "read_passenger", "read_plane"})
     */
    private $arrival_time;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="flights")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_flight", "write_flight", "read_airport", "read_gate", "read_passenger"})
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Airport", inversedBy="flights")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_flight", "write_flight"})
     */
    private $airport;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Plane", inversedBy="flights")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_flight", "write_flight"})
     */
    private $plane;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Passenger", mappedBy="flight")
     * @ApiSubresource
     * @Groups({"read_flight", "write_flight"})
     */
    private $passengers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Gate", mappedBy="flight")
     * @ApiSubresource
     * @Groups({"read_flight", "write_flight"})
     */
    private $gates;

    public function __construct()
    {
        $this->passengers = new ArrayCollection();
        $this->gates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeparture(): ?string
    {
        return $this->departure;
    }

    public function setDeparture(string $departure): self
    {
        $this->departure = $departure;

        return $this;
    }

    public function getArrival(): ?string
    {
        return $this->arrival;
    }

    public function setArrival(string $arrival): self
    {
        $this->arrival = $arrival;

        return $this;
    }

    public function getDepartureTime(): ?\DateTimeInterface
    {
        return $this->departure_time;
    }

    public function setDepartureTime(\DateTimeInterface $departure_time): self
    {
        $this->departure_time = $departure_time;

        return $this;
    }

    public function getArrivalTime(): ?\DateTimeInterface
    {
        return $this->arrival_time;
    }

    public function setArrivalTime(\DateTimeInterface $arrival_time): self
    {
        $this->arrival_time = $arrival_time;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getAirport(): ?Airport
    {
        return $this->airport;
    }

    public function setAirport(?Airport $airport): self
    {
        $this->airport = $airport;

        return $this;
    }

    public function getPlane(): ?Plane
    {
        return $this->plane;
    }

    public function setPlane(?Plane $plane): self
    {
        $this->plane = $plane;

        return $this;
    }

    /**
     * @return Collection|Passenger[]
     */
    public function getPassengers(): Collection
    {
        return $this->passengers;
    }

    public function addPassenger(Passenger $passenger): self
    {
        if (!$this->passengers->contains($passenger)) {
            $this->passengers[] = $passenger;
            $passenger->setFlight($this);
        }

        return $this;
    }

    public function removePassenger(Passenger $passenger): self
    {
        if ($this->passengers->contains($passenger)) {
            $this->passengers->removeElement($passenger);
            // set the owning side to null (unless already changed)
            if ($passenger->getFlight() === $this) {
                $passenger->setFlight(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Gate[]
     */
    public function getGates(): Collection
    {
        return $this->gates;
    }

    public function addGate(Gate $gate): self
    {
        if (!$this->gates->contains($gate)) {
            $this->gates[] = $gate;
            $gate->setFlight($this);
        }

        return $this;
    }

    public function removeGate(Gate $gate): self
    {
        if ($this->gates->contains($gate)) {
            $this->gates->removeElement($gate);
            // set the owning side to null (unless already changed)
            if ($gate->getFlight() === $this) {
                $gate->setFlight(null);
            }
        }

        return $this;
    }
}
