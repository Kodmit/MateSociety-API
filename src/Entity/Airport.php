<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
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
 *          "delete",
 *          "get",
 *          "put"={"access_control"="is_granted('ROLE_WRITER')"}
 *     },
 *     normalizationContext={"groups"={"read_airport"}},
 *     denormalizationContext={"groups"={"write_airport"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\AirportRepository")
 */
class Airport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=70)
     * @Assert\Length(
     *      min = 2,
     *      max = 70,
     *      minMessage = "The name must be at least {{ limit }} characters long",
     *      maxMessage = "The name cannot be longer than {{ limit }} characters"
     * )
     * @Groups({"read_airport", "write_airport", "read_control_tower", "read_flight", "read_gate", "read_plane"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=70)
     * @Assert\Length(
     *      min = 2,
     *      max = 70,
     *      minMessage = "City must be at least {{ limit }} characters long",
     *      maxMessage = "City cannot be longer than {{ limit }} characters"
     * )
     * @Groups({"read_airport", "write_airport", "read_control_tower", "read_flight", "read_gate", "read_plane"})
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="airports")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_airport", "write_airport"})
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Plane", mappedBy="airport", orphanRemoval=true)
     * @Groups({"read_airport", "write_airport"})
     */
    private $planes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Flight", mappedBy="airport")
     * @ApiSubresource
     * @Groups({"read_airport", "write_airport"})
     */
    private $flights;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Track", mappedBy="airport", orphanRemoval=true)
     * @ApiSubresource
     * @ApiSubresource
     * @Groups({"read_airport", "write_airport"})
     */
    private $tracks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ControlTower", mappedBy="airport", orphanRemoval=true)
     * @ApiSubresource
     * @Groups({"read_airport", "write_airport"})
     */
    private $controlTowers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Gate", mappedBy="airport", orphanRemoval=true)
     * @ApiSubresource
     * @Groups({"read_airport", "write_airport"})
     */
    private $gates;

    public function __construct()
    {
        $this->planes = new ArrayCollection();
        $this->flights = new ArrayCollection();
        $this->tracks = new ArrayCollection();
        $this->controlTowers = new ArrayCollection();
        $this->gates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection|Plane[]
     */
    public function getPlanes(): Collection
    {
        return $this->planes;
    }

    public function addPlane(Plane $plane): self
    {
        if (!$this->planes->contains($plane)) {
            $this->planes[] = $plane;
            $plane->setAirport($this);
        }

        return $this;
    }

    public function removePlane(Plane $plane): self
    {
        if ($this->planes->contains($plane)) {
            $this->planes->removeElement($plane);
            // set the owning side to null (unless already changed)
            if ($plane->getAirport() === $this) {
                $plane->setAirport(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Flight[]
     */
    public function getFlights(): Collection
    {
        return $this->flights;
    }

    public function addFlight(Flight $flight): self
    {
        if (!$this->flights->contains($flight)) {
            $this->flights[] = $flight;
            $flight->setAirport($this);
        }

        return $this;
    }

    public function removeFlight(Flight $flight): self
    {
        if ($this->flights->contains($flight)) {
            $this->flights->removeElement($flight);
            // set the owning side to null (unless already changed)
            if ($flight->getAirport() === $this) {
                $flight->setAirport(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Track[]
     */
    public function getTracks(): Collection
    {
        return $this->tracks;
    }

    public function addTrack(Track $track): self
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks[] = $track;
            $track->setAirport($this);
        }

        return $this;
    }

    public function removeTrack(Track $track): self
    {
        if ($this->tracks->contains($track)) {
            $this->tracks->removeElement($track);
            // set the owning side to null (unless already changed)
            if ($track->getAirport() === $this) {
                $track->setAirport(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ControlTower[]
     */
    public function getControlTowers(): Collection
    {
        return $this->controlTowers;
    }

    public function addControlTower(ControlTower $controlTower): self
    {
        if (!$this->controlTowers->contains($controlTower)) {
            $this->controlTowers[] = $controlTower;
            $controlTower->setAirport($this);
        }

        return $this;
    }

    public function removeControlTower(ControlTower $controlTower): self
    {
        if ($this->controlTowers->contains($controlTower)) {
            $this->controlTowers->removeElement($controlTower);
            // set the owning side to null (unless already changed)
            if ($controlTower->getAirport() === $this) {
                $controlTower->setAirport(null);
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
            $gate->setAirport($this);
        }

        return $this;
    }

    public function removeGate(Gate $gate): self
    {
        if ($this->gates->contains($gate)) {
            $this->gates->removeElement($gate);
            // set the owning side to null (unless already changed)
            if ($gate->getAirport() === $this) {
                $gate->setAirport(null);
            }
        }

        return $this;
    }
}
