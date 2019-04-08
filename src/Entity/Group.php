<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 * @UniqueEntity("name")
 * @ORM\Table(name="groups")
 */
class Group
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=160, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="owned_group", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="groups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupFeed", mappedBy="_group", orphanRemoval=true)
     */
    private $groupFeeds;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupInfo", mappedBy="_group", orphanRemoval=true)
     */
    private $groupInfos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupGoal", mappedBy="_group", orphanRemoval=true)
     */
    private $groupGoals;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\JoinRequest", mappedBy="_group", orphanRemoval=true)
     */
    private $joinRequests;

    public function __construct()
    {
        $this->groupFeeds = new ArrayCollection();
        $this->groupInfos = new ArrayCollection();
        $this->groupGoals = new ArrayCollection();
        $this->joinRequests = new ArrayCollection();
        $this->created_at = new \DateTime();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|GroupFeed[]
     */
    public function getGroupFeeds(): Collection
    {
        return $this->groupFeeds;
    }

    public function addGroupFeed(GroupFeed $groupFeed): self
    {
        if (!$this->groupFeeds->contains($groupFeed)) {
            $this->groupFeeds[] = $groupFeed;
            $groupFeed->setGroup($this);
        }

        return $this;
    }

    public function removeGroupFeed(GroupFeed $groupFeed): self
    {
        if ($this->groupFeeds->contains($groupFeed)) {
            $this->groupFeeds->removeElement($groupFeed);
            // set the owning side to null (unless already changed)
            if ($groupFeed->getGroup() === $this) {
                $groupFeed->setGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupInfo[]
     */
    public function getGroupInfos(): Collection
    {
        return $this->groupInfos;
    }

    public function addGroupInfo(GroupInfo $groupInfo): self
    {
        if (!$this->groupInfos->contains($groupInfo)) {
            $this->groupInfos[] = $groupInfo;
            $groupInfo->setGroup($this);
        }

        return $this;
    }

    public function removeGroupInfo(GroupInfo $groupInfo): self
    {
        if ($this->groupInfos->contains($groupInfo)) {
            $this->groupInfos->removeElement($groupInfo);
            // set the owning side to null (unless already changed)
            if ($groupInfo->getGroup() === $this) {
                $groupInfo->setGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupGoal[]
     */
    public function getGroupGoals(): Collection
    {
        return $this->groupGoals;
    }

    public function addGroupGoal(GroupGoal $groupGoal): self
    {
        if (!$this->groupGoals->contains($groupGoal)) {
            $this->groupGoals[] = $groupGoal;
            $groupGoal->setGroup($this);
        }

        return $this;
    }

    public function removeGroupGoal(GroupGoal $groupGoal): self
    {
        if ($this->groupGoals->contains($groupGoal)) {
            $this->groupGoals->removeElement($groupGoal);
            // set the owning side to null (unless already changed)
            if ($groupGoal->getGroup() === $this) {
                $groupGoal->setGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|JoinRequest[]
     */
    public function getJoinRequests(): Collection
    {
        return $this->joinRequests;
    }

    public function addJoinRequest(JoinRequest $joinRequest): self
    {
        if (!$this->joinRequests->contains($joinRequest)) {
            $this->joinRequests[] = $joinRequest;
            $joinRequest->setGroup($this);
        }

        return $this;
    }

    public function removeJoinRequest(JoinRequest $joinRequest): self
    {
        if ($this->joinRequests->contains($joinRequest)) {
            $this->joinRequests->removeElement($joinRequest);
            // set the owning side to null (unless already changed)
            if ($joinRequest->getGroup() === $this) {
                $joinRequest->setGroup(null);
            }
        }

        return $this;
    }
}
