<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

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
 *     normalizationContext={"groups"={"read_company"}},
 *     denormalizationContext={"groups"={"write_company"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
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
     * )
     * @Groups({"read_company", "write_company", "read_airport", "read_flight", "read_passenger", "read_crew", "read_plane"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(
     *      min = 5,
     *      max = 100,
     * )
     * @Groups({"read_company", "write_company", "read_airport", "read_flight", "read_crew", "read_plane"})
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="companies")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_company", "write_company"})
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Plane", mappedBy="company")
     * @ApiSubresource
     * @Groups({"read_company", "write_company"})
     */
    private $planes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Flight", mappedBy="company")
     * @ApiSubresource
     * @Groups({"read_company", "write_company"})
     */
    private $flights;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Crew", mappedBy="company", orphanRemoval=true)
     * @ApiSubresource
     * @Groups({"read_company", "write_company"})
     */
    private $crews;

    public function __construct()
    {
        $this->planes = new ArrayCollection();
        $this->flights = new ArrayCollection();
        $this->crews = new ArrayCollection();
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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

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
            $plane->setCompany($this);
        }

        return $this;
    }

    public function removePlane(Plane $plane): self
    {
        if ($this->planes->contains($plane)) {
            $this->planes->removeElement($plane);
            // set the owning side to null (unless already changed)
            if ($plane->getCompany() === $this) {
                $plane->setCompany(null);
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
            $flight->setCompany($this);
        }

        return $this;
    }

    public function removeFlight(Flight $flight): self
    {
        if ($this->flights->contains($flight)) {
            $this->flights->removeElement($flight);
            // set the owning side to null (unless already changed)
            if ($flight->getCompany() === $this) {
                $flight->setCompany(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Crew[]
     */
    public function getCrews(): Collection
    {
        return $this->crews;
    }

    public function addCrew(Crew $crew): self
    {
        if (!$this->crews->contains($crew)) {
            $this->crews[] = $crew;
            $crew->setCompany($this);
        }

        return $this;
    }

    public function removeCrew(Crew $crew): self
    {
        if ($this->crews->contains($crew)) {
            $this->crews->removeElement($crew);
            // set the owning side to null (unless already changed)
            if ($crew->getCompany() === $this) {
                $crew->setCompany(null);
            }
        }

        return $this;
    }
}
