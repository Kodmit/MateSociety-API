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
 *     normalizationContext={"groups"={"read_crew_type"}},
 *     denormalizationContext={"groups"={"write_crew_type"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CrewTypeRepository")
 */
class CrewType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\Length(
     *      min = 2,
     *      max = 150,
     * )
     * @Groups({"read_crew_type", "write_crew_type", "read_company", "read_crew"})
     */
    private $job;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read_crew_type", "write_crew_type", "read_company", "read_crew"})
     */
    private $level;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Crew", mappedBy="crewType")
     * @ApiSubresource
     * @Groups({"read_crew_type", "write_crew_type"})
     */
    private $crews;

    public function __construct()
    {
        $this->crews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

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
            $crew->setCrewType($this);
        }

        return $this;
    }

    public function removeCrew(Crew $crew): self
    {
        if ($this->crews->contains($crew)) {
            $this->crews->removeElement($crew);
            // set the owning side to null (unless already changed)
            if ($crew->getCrewType() === $this) {
                $crew->setCrewType(null);
            }
        }

        return $this;
    }
}
