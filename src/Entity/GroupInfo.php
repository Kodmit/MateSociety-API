<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as KodAssert;

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
 *     normalizationContext={"groups"={"read_info"}},
 *     denormalizationContext={"groups"={"write_info"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\GroupInfoRepository")
 * @ORM\Table(name="group_infos")
 */
class GroupInfo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Group", inversedBy="groupInfos")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_info", "write_info"})
     * @KodAssert\IsCreator
     */
    public $_group;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"read_info", "write_info"})
     */
    private $icon;

    /**
     * @ORM\Column(type="string", length=200)
     * @Groups({"read_info", "write_info"})
     */
    private $wording;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read_info", "write_info"})
     */
    private $value;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"read_info", "write_info"})
     */
    private $visibility;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read_info"})
     */
    public $created_at;

    public function __construct()
    {
        $this->created_at = new \DateTime();
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

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getWording(): ?string
    {
        return $this->wording;
    }

    public function setWording(string $wording): self
    {
        $this->wording = $wording;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(string $visibility): self
    {
        $this->visibility = $visibility;

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
}
