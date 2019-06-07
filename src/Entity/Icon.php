<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "get"
 *     },
 *     itemOperations={
 *         "get"
 *     },
 *     normalizationContext={"groups"={"read_icon"}},
 *     denormalizationContext={"groups"={"write_icon"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\IconRepository")
 * @ORM\Table(name="icons")
 */
class Icon
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read_icon", "read_group_goal"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"read_icon", "read_group_goal"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"read_icon", "read_group_goal", "read_group"})
     */
    private $path;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupGoal", mappedBy="icon")
     * @Groups({"read_icon"})
     */
    private $groupGoal;

    public function __construct()
    {
        $this->groupGoal = new ArrayCollection();
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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return Collection|GroupGoal[]
     */
    public function getGroupGoal(): Collection
    {
        return $this->groupGoal;
    }

    public function addGroupGoal(GroupGoal $groupGoal): self
    {
        if (!$this->groupGoal->contains($groupGoal)) {
            $this->groupGoal[] = $groupGoal;
            $groupGoal->setIcon($this);
        }

        return $this;
    }

    public function removeGroupGoal(GroupGoal $groupGoal): self
    {
        if ($this->groupGoal->contains($groupGoal)) {
            $this->groupGoal->removeElement($groupGoal);
            // set the owning side to null (unless already changed)
            if ($groupGoal->getIcon() === $this) {
                $groupGoal->setIcon(null);
            }
        }

        return $this;
    }
}
