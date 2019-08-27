<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as KodAssert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     attributes={
 *          "access_control"="is_granted('ROLE_USER')"
 *      },
 *     collectionOperations={
 *         "get"={"access_control"="is_granted('ROLE_ADMIN')"},
 *         "post"
 *     },
 *     itemOperations={
 *          "get",
 *          "put"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"},
 *          "delete"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"}
 *     },
 *     normalizationContext={"groups"={"read_event"}},
 *     denormalizationContext={"groups"={"write_event"}}
 *     )
 * @ORM\Entity(repositoryClass="App\Repository\GroupEventRepository")
 * @ORM\Table(name="group_events")
 */
class GroupEvent
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Groups({"is_member:group_event", "write_event", "read_group"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"is_member:group_event", "write_event"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"is_member:group_event", "read_event"})
     */
    public $creator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Group", inversedBy="groupEvents")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"is_member:group_event"})
     */
    public $_group;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"is_member:group_event"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"is_member:group_event", "write_event", "read_group"})
     */
    public $start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"is_member:group_event", "write_event", "read_group"})
     */
    public $end;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"is_member:group_event", "write_event", "read_group"})
     */
    private $place;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"is_member:group_event", "write_event", "read_group"})
     */
    private $rrule;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"is_member:group_event", "write_event", "read_group"})
     */
    private $allDay;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"is_member:group_event", "write_event", "read_group"})
     */
    private $duration;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getGroup(): ?Group
    {
        return $this->_group;
    }

    public function setGroup(?Group $_group): self
    {
        $this->_group = $_group;

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

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(?\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getRrule()
    {
        return $this->rrule;
    }

    public function setRrule($rrule): self
    {
        $this->rrule = $rrule;

        return $this;
    }

    public function getAllDay(): ?bool
    {
        return $this->allDay;
    }

    public function setAllDay(?bool $allDay): self
    {
        $this->allDay = $allDay;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
}
