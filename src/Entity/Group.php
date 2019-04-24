<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;

/**
 * @ApiResource(
 *     attributes={
 *          "access_control"="is_granted('ROLE_USER')"
 *      },
 *     collectionOperations={
 *         "get",
 *         "post"
 *     },
 *     itemOperations={
 *          "get",
 *          "put"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"},
 *          "delete"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"}
 *     },
 *     normalizationContext={"groups"={"read_group"}},
 *     denormalizationContext={"groups"={"write_group"}}
 * )
 * @ApiFilter(SearchFilter::class, properties={"name": "partial", "city": "exact", "description": "partial"})
 * @ORM\Entity(repositoryClass="App\Repository\GroupRepository")
 * @UniqueEntity("name")
 * @ORM\Table(name="groups")
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=160, unique=true)
     * @Groups({"read_group", "write_group", "read_request", "read_user"})
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read_group"})
     */
    public $created_at;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="owned_group", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_group"})
     */
    private $creator;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read_group", "write_group"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="groups")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_group", "write_group"})
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=200)
     * @Groups({"read_group", "write_group"})
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupFeed", mappedBy="_group", orphanRemoval=true)
     * @Groups({"is_creator:group", "is_admin"})
     */
    private $groupFeeds;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupInfo", mappedBy="_group", orphanRemoval=true)
     * @ApiSubresource
     * @Groups({"is_creator:group", "is_admin"})
     */
    private $groupInfos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupGoal", mappedBy="_group", orphanRemoval=true)
     * @Groups({"is_creator:group", "is_admin"})
     */
    private $groupGoals;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\JoinRequest", mappedBy="_group", orphanRemoval=true)
     * @ApiSubresource
     * @Groups({"is_creator:group", "is_admin"})
     */
    private $joinRequests;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="group_member")
     * @Groups({"is_creator:group", "is_admin"})
     */
    private $users;

    public function __construct()
    {
        $this->groupFeeds = new ArrayCollection();
        $this->groupInfos = new ArrayCollection();
        $this->groupGoals = new ArrayCollection();
        $this->joinRequests = new ArrayCollection();
        $this->created_at = new \DateTime();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?string
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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setGroupMember($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getGroupMember() === $this) {
                $user->setGroupMember(null);
            }
        }

        return $this;
    }
}
