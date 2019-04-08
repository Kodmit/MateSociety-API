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
 *     normalizationContext={"groups"={"read_crew"}},
 *     denormalizationContext={"groups"={"write_crew"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\CrewRepository")
 */
class Crew
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(
     *      min = 2,
     *      max = 100,
     * )
     * @Groups({"read_crew", "write_crew", "read_company", "read_crew_type"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(
     *      min = 2,
     *      max = 100,
     * )
     * @Groups({"read_crew", "write_crew", "read_company", "read_crew_type"})
     */
    private $firstname;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="crews")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_crew", "write_crew"})
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CrewType", inversedBy="crews")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_crew", "write_crew", "read_company"})
     */
    private $crewType;

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

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getCrewType(): ?CrewType
    {
        return $this->crewType;
    }

    public function setCrewType(?CrewType $crewType): self
    {
        $this->crewType = $crewType;

        return $this;
    }
}
