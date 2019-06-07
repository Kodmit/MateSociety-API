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
    private $name;

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
     * @ORM\Column(type="datetime")
     * @Groups({"is_member:group_event", "write_event", "read_group"})
     */
    public $event_start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"is_member:group_event", "write_event", "read_group"})
     */
    public $event_end;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"is_member:group_event", "write_event", "read_group"})
     */
    private $place;

    public function __construct()
    {
        $this->created_at = new \DateTime();
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

    public function getEventStart(): ?\DateTimeInterface
    {
        return $this->event_start;
    }

    public function setEventStart(\DateTimeInterface $event_start): self
    {
        $this->event_start = $event_start;

        return $this;
    }

    public function getEventEnd(): ?\DateTimeInterface
    {
        return $this->event_end;
    }

    public function setEventEnd(?\DateTimeInterface $event_end): self
    {
        $this->event_end = $event_end;

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
}
