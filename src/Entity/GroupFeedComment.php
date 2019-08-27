<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * attributes={
 *          "access_control"="is_granted('ROLE_USER')",
 *          "normalization_context"={"groups"={"read_feedComment"}},
 *          "denormalization_context"={"groups"={"write_feedComment"}},
 *          "pagination_items_per_page"=2,
 *          "order"={"created_at": "DESC"}
 *      },
 *     collectionOperations={
 *         "get"={"access_control"="is_granted('ROLE_ADMIN')"},
 *         "post"
 *     },
 *     itemOperations={
 *          "get"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"},
 *          "put"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"},
 *          "delete"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\GroupFeedCommentRepository")
 * @ORM\Table(name="group_feed_comments")
 */
class GroupFeedComment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="groupFeedComments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_feedComment", "read_feed"})
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GroupFeed", inversedBy="groupFeedComments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"write_feedComment"})
     */
    public $group_feed;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read_feedComment", "read_feed"})
     */
    public $created_at;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read_feedComment", "write_feedComment", "read_feed"})
     */
    private $content;

    /**
     * @ORM\Column(type="integer")
     */
    private $likes;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->likes = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

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

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }
}
