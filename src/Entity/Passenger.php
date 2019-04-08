<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
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
 *     normalizationContext={"groups"={"read_passenger"}},
 *     denormalizationContext={"groups"={"write_passenger"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\PassengerRepository")
 */
class Passenger
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
     * @Groups({"read_passenger", "write_passenger", "read_flight"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=70)
     * @Assert\Length(
     *      min = 2,
     *      max = 70,
     * )
     * @Groups({"read_passenger", "write_passenger", "read_flight"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type("bool")
     * @Groups({"read_passenger", "write_passenger", "read_flight"})
     */
    private $sex;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="passengers")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_passenger", "write_passenger"})
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Flight", inversedBy="passengers")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_passenger", "write_passenger"})
     */
    private $flight;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getSex(): ?bool
    {
        return $this->sex;
    }

    public function setSex(bool $sex): self
    {
        $this->sex = $sex;

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

    public function getFlight(): ?Flight
    {
        return $this->flight;
    }

    public function setFlight(?Flight $flight): self
    {
        $this->flight = $flight;

        return $this;
    }
}
