<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *     attributes={
 *          "access_control"="is_granted('ROLE_USER')",
 *          "normalization_context"={"groups"={"read_feed"}},
 *          "denormalization_context"={"groups"={"write_feed"}},
 *          "pagination_items_per_page"=5,
 *          "order"={"created_at": "DESC"}
 *      },
 *     collectionOperations={
 *         "get"={"access_control"="is_granted('ROLE_ADMIN')"},
 *         "post"
 *     },
 *     itemOperations={
 *           "like"={
 *              "denormalization_context"={"groups"={"write_like"}},
 *              "controller"=App\Controller\GroupFeedLike::class,
 *              "path"="/group_feeds/{id}/like",
 *              "method"="GET",
 *              "swagger_context" = {
 *                  "summary" = "Add or remove a like on GroupFeed post.",
 *                  "parameters" = {
 *                      {
 *                          "name" = "id",
 *                          "in" = "path",
 *                          "description" = "GroupFeed ID.",
 *                          "required" = "true",
 *                          "type" : "integer",
 *                      }
 *                  }
 *               }
 *          },
 *          "get"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"},
 *          "put"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"},
 *          "delete"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"}
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"public": "exact"})
 * @ORM\Entity(repositoryClass="App\Repository\GroupFeedRepository")
 * @ORM\Table(name="group_feeds")
 */
class GroupFeed
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Group", inversedBy="groupFeeds")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_feed", "write_feed"})
     */
    public $_group;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="groupFeeds")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_feed"})
     */
    private $creator;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read_feed", "write_feed"})
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read_feed"})
     */
    public $created_at;

    /**
     * @ORM\Column(type="array")
     * @Groups({"write_like", "read_feed"})
     */
    private $likes = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupFeedComment", mappedBy="group_feed", orphanRemoval=true)
     * @Groups({"read_feed"})
     */
    private $groupFeedComments;

    /**
     * @Groups({"read_feed"})
     * @ORM\OneToMany(targetEntity="App\Entity\ImageObject", mappedBy="group_feed")
     */
    private $imageObjects;

    /**
     * @Groups({"read_feed", "write_feed"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $public;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->groupFeedComments = new ArrayCollection();
        $this->imageObjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getLikes(): ?array
    {
        return $this->likes;
    }

    public function setLikes(array $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    /**
     * @return Collection|GroupFeedComment[]
     */
    public function getGroupFeedComments(): Collection
    {
        return $this->groupFeedComments;
    }

    public function addGroupFeedComment(GroupFeedComment $groupFeedComment): self
    {
        if (!$this->groupFeedComments->contains($groupFeedComment)) {
            $this->groupFeedComments[] = $groupFeedComment;
            $groupFeedComment->setGroupFeed($this);
        }

        return $this;
    }

    public function removeGroupFeedComment(GroupFeedComment $groupFeedComment): self
    {
        if ($this->groupFeedComments->contains($groupFeedComment)) {
            $this->groupFeedComments->removeElement($groupFeedComment);
            // set the owning side to null (unless already changed)
            if ($groupFeedComment->getGroupFeed() === $this) {
                $groupFeedComment->setGroupFeed(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ImageObject[]
     */
    public function getImageObjects(): Collection
    {
        return $this->imageObjects;
    }

    public function addImageObject(ImageObject $imageObject): self
    {
        if (!$this->imageObjects->contains($imageObject)) {
            $this->imageObjects[] = $imageObject;
            $imageObject->setGroupFeed($this);
        }

        return $this;
    }

    public function removeImageObject(ImageObject $imageObject): self
    {
        if ($this->imageObjects->contains($imageObject)) {
            $this->imageObjects->removeElement($imageObject);
            // set the owning side to null (unless already changed)
            if ($imageObject->getGroupFeed() === $this) {
                $imageObject->setGroupFeed(null);
            }
        }

        return $this;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(?bool $public): self
    {
        $this->public = $public;

        return $this;
    }
}
