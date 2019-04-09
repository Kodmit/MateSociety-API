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
 *         "get"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user or object._group.creator == user"},
 *         "post"
 *     },
 *     itemOperations={
 *          "get"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"},
 *          "put"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"},
 *          "delete"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"}
 *     },
 *     normalizationContext={"groups"={"read_request"}},
 *     denormalizationContext={"groups"={"write_request"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\JoinRequestRepository")
 * @ORM\Table(name="join_requests")
 */
class JoinRequest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="joinRequests")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_request"})
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Group", inversedBy="joinRequests")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_request", "write_request"})
     */
    public $_group;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read_request"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"read_request"})
     */
    private $updated_at;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read_request", "write_request", "read_group"})
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"read_request"})
     */
    private $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"read_request"})
     */
    private $response;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->status = "awaiting";
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getResponse(): ?string
    {
        return $this->response;
    }

    public function setResponse(?string $response): self
    {
        $this->response = $response;

        return $this;
    }
}
