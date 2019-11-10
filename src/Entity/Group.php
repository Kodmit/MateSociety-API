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
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use App\Filter\MsDateFilter;

/**
 * @ApiResource(
 *     attributes={
 *          "access_control"="is_granted('ROLE_USER')",
 *          "pagination_items_per_page"=6,
 *          "filters"={MsDateFilter::class},
 *      },
 *     collectionOperations={
 *         "get",
 *         "post",
*          "get_nearest_groups"={
*              "controller"=App\Controller\Group\GetNearestGroups::class,
*              "path"="/get_nearest_groups",
*              "method"="GET",
*              "swagger_context" = {
*                  "summary" = "Return the nearest groups.",
*               }
*          },
 *         "most_popular_groups"={
 *              "controller"=App\Controller\Group\MostPopularGroups::class,
 *              "path"="/most_popular_groups",
 *              "method"="GET",
 *              "swagger_context" = {
 *                  "summary" = "Return the most popular groups.",
 *               }
 *          },
 *          "latest_groups"={
 *              "controller"=App\Controller\Group\LastGroups::class,
 *              "path"="/get_latest_groups",
 *              "method"="GET",
 *              "swagger_context" = {
 *                  "summary" = "Return the last groups.",
 *               }
 *          },
 *     },
 *     itemOperations={
 *          "get",
 *          "put"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"},
 *          "delete"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"}
 *     },
 *     normalizationContext={"groups"={"read_group"}},
 *     denormalizationContext={"groups"={"write_group"}}
 * )
 * @ApiFilter(SearchFilter::class, properties={"name": "partial", "department": "exact", "groupInterests": "exact"})
 * @ApiFilter(OrderFilter::class)
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
     * @Groups({"read_group", "write_group", "read_request", "read_user", "read_feed"})
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read_group"})
     */
    private $createdAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="owned_group", cascade={"persist", "remove"}    )
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_group"})
     */
    public $creator;

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
     * @var MediaObject|null
     *
     * @ORM\ManyToOne(targetEntity=MediaObject::class)
     * @ORM\JoinColumn(nullable=true)
     * @ApiProperty(iri="http://schema.org/image")
     * @Groups({"read_group", "write_group"})
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupFeed", mappedBy="_group", orphanRemoval=true)
     * @ApiSubresource
     * @Groups({"read_group", "is_admin"})
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
     * @Groups({"read_group"})
     * @ApiSubresource
     */
    private $groupGoals;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\JoinRequest", mappedBy="_group", orphanRemoval=true)
     * @ApiSubresource
     * @Groups({"is_creator:group", "is_admin"})
     */
    private $joinRequests;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupEvent", mappedBy="_group", orphanRemoval=true)
     * todo : Add is_member normalizer
     * @Groups({"read_group"})
     * @ApiSubresource
     */
    private $groupEvents;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Department", inversedBy="_groups")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_group"})
     */
    private $department;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="groupsMember")
     * @Groups({"read_group"})
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\GroupInterest", mappedBy="msGroup")
     * @Groups({"read_group"})
     */
    private $groupInterests;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"read_group", "write_group"})
     */
    private $pictureProfile;

    public function __construct()
    {
        $this->groupFeeds = new ArrayCollection();
        $this->groupInfos = new ArrayCollection();
        $this->groupGoals = new ArrayCollection();
        $this->joinRequests = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->users = new ArrayCollection();
        $this->groupEvents = new ArrayCollection();
        $this->userList = new ArrayCollection();
        $this->groupInterests = new ArrayCollection();
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
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getImage(): ?MediaObject
    {
        return $this->image;
    }

    public function setImage(?MediaObject $image): self
    {
        if (isset($this->image)) {
            unlink("../public/uploads/media/" . $this->image->filePath);
        }

        $this->image = $image;
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
     * @return Collection|GroupEvent[]
     */
    public function getGroupEvents(): Collection
    {
        return $this->groupEvents;
    }

    public function addGroupEvent(GroupEvent $groupEvent): self
    {
        if (!$this->groupEvents->contains($groupEvent)) {
            $this->groupEvents[] = $groupEvent;
            $groupEvent->setGroup($this);
        }

        return $this;
    }

    public function removeGroupEvent(GroupEvent $groupEvent): self
    {
        if ($this->groupEvents->contains($groupEvent)) {
            $this->groupEvents->removeElement($groupEvent);
            // set the owning side to null (unless already changed)
            if ($groupEvent->getGroup() === $this) {
                $groupEvent->setGroup(null);
            }
        }

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

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
            $user->addGroupsMember($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeGroupsMember($this);
        }

        return $this;
    }

    /**
     * @return Collection|GroupInterest[]
     */
    public function getGroupInterests(): Collection
    {
        return $this->groupInterests;
    }

    public function addGroupInterest(GroupInterest $groupInterest): self
    {
        if (!$this->groupInterests->contains($groupInterest)) {
            $this->groupInterests[] = $groupInterest;
            $groupInterest->addMsGroup($this);
        }

        return $this;
    }

    public function removeGroupInterest(GroupInterest $groupInterest): self
    {
        if ($this->groupInterests->contains($groupInterest)) {
            $this->groupInterests->removeElement($groupInterest);
            $groupInterest->removeMsGroup($this);
        }

        return $this;
    }

    public function getPictureProfile(): ?string
    {
        return $this->pictureProfile;
    }

    public function setPictureProfile(?string $pictureProfile): self
    {
        $this->pictureProfile = $pictureProfile;

        return $this;
    }
}
