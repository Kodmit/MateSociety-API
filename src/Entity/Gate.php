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
 *          "delete"={"access_control"="is_granted('ROLE_WRITER')"},
 *          "get",
 *          "put"={"access_control"="is_granted('ROLE_WRITER')"}
 *     },
 *     normalizationContext={"groups"={"read_gate"}},
 *     denormalizationContext={"groups"={"write_gate"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\GateRepository")
 */
class Gate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\Type("bool")
     * @Groups({"read_gate", "write_gate", "read_airport", "read_flight"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Flight", inversedBy="gates")
     * @Groups({"read_gate", "write_gate", "read_airport"})
     */
    private $flight;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Airport", inversedBy="gates")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_gate", "write_gate"})
     */
    private $airport;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

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

    public function getAirport(): ?Airport
    {
        return $this->airport;
    }

    public function setAirport(?Airport $airport): self
    {
        $this->airport = $airport;

        return $this;
    }
}
