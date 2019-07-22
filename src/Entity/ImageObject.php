<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

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
 *         "get",
 *          "put"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"},
 *          "delete"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"}
 *     },
 *     normalizationContext={"groups"={"read_image"}},
 *     denormalizationContext={"groups"={"write_image"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ImageObjectRepository")
 * @ORM\Table(name="image_objects")
 */
class ImageObject
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @Groups({"read_image", "write_image"})
     * @ORM\Column(type="boolean")
     */
    private $by_default;

    /**
     * @Groups({"read_image", "write_image"})
     * @ORM\Column(type="boolean")
     */
    private $highlight;

    /**
     * @Groups({"read_image", "write_image"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups({"read_image", "write_image"})
     * @ORM\Column(type="text")
     */
    private $path;

    /**
     * @Groups({"read_image"})
     * @ORM\Column(type="datetime")
     */
    public $created_at;

    /**
     * @Groups({"read_image"})
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="imageObjects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    /**
     * @Groups({"read_image", "write_image"})
     * @ORM\ManyToOne(targetEntity="App\Entity\GroupFeed", inversedBy="imageObjects")
     */
    public $group_feed;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getByDefault(): ?bool
    {
        return $this->by_default;
    }

    public function setByDefault(bool $by_default): self
    {
        $this->by_default = $by_default;

        return $this;
    }

    public function getHighlight(): ?bool
    {
        return $this->highlight;
    }

    public function setHighlight(bool $highlight): self
    {
        $this->highlight = $highlight;

        return $this;
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

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getGroupFeed(): ?GroupFeed
    {
        return $this->group_feed;
    }

    public function setGroupFeed(?GroupFeed $group_feed): self
    {
        $this->group_feed = $group_feed;

        return $this;
    }
}
