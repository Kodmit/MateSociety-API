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
 *     normalizationContext={"groups"={"read_control_tower"}},
 *     denormalizationContext={"groups"={"write_control_tower"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ControlTowerRepository")
 */
class ControlTower
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
     * @Groups({"read_control_tower", "write_control_tower", "read_airport"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Airport", inversedBy="controlTowers")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_control_tower", "write_control_tower"})
     */
    private $airport;

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
