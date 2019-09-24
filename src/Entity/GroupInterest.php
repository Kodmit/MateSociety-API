<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     attributes={
 *         "access_control"="is_granted('ROLE_USER')"
 *     },
 *     collectionOperations={
 *         "get",
 *         "post",
 *          "toggle_group"={
 *              "controller"=App\Controller\ToggleGroup::class,
 *              "path"="/toggle_group_interest/",
 *              "method"="GET",
 *              "swagger_context" = {
 *                  "summary" = "Toggle a GroupInterest for the specified Group",
 *                  "parameters" = {
 *                      {
 *                          "name" = "id",
 *                          "in" = "path",
 *                          "description" = "The GroupInterest ID",
 *                          "required" = "true",
 *                          "type" : "string",
 *                      },
 *                      {
 *                          "name" = "group",
 *                          "in" = "path",
 *                          "description" = "The Group ID",
 *                          "required" = "true",
 *                          "type" : "string",
 *                      }
 *                  }
 *               }
 *          },
 *     },
 *     itemOperations={
 *         "get",
 *         "put"={"access_control"="is_granted('ROLE_ADMIN')"},
 *         "delete"={"access_control"="is_granted('ROLE_ADMIN')"},
 *         "toggle_user"={
 *              "controller"=App\Controller\GroupInterest\ToggleUser::class,
 *              "path"="/toggle_user_interest/{id}",
 *              "method"="GET",
 *              "swagger_context" = {
 *                  "summary" = "Toggle a GroupInterest for the current user",
 *                  "parameters" = {
 *                      {
 *                          "name" = "id",
 *                          "in" = "path",
 *                          "description" = "The GroupInterest ID",
 *                          "required" = "true",
 *                          "type" : "string",
 *                      }
 *                  }
 *               }
 *          },
 *     },
 *     normalizationContext={"groups"={"read_group_interests"}},
 *     denormalizationContext={"groups"={"write_group_interests"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\GroupInterestRepository")
 */
class GroupInterest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read_group_interests", "write_group_interests", "read_group"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Group", inversedBy="groupInterests")
     * @Groups({"read_group_interests"})
     */
    private $msGroup;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Icon", inversedBy="groupInterests")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_group_interests", "write_group_interests"})
     */
    private $icon;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="groupInterests")
     * @Groups({"read_group_interests"})
     * @ApiSubresource
     */
    private $user;

    public function __construct()
    {
        $this->msGroup = new ArrayCollection();
        $this->created_at = new \DateTime();
        $this->user = new ArrayCollection();
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

    /**
     * @return Collection|Group[]
     */
    public function getMsGroup(): Collection
    {
        return $this->msGroup;
    }

    public function addMsGroup(Group $msGroup): self
    {
        if (!$this->msGroup->contains($msGroup)) {
            $this->msGroup[] = $msGroup;
        }

        return $this;
    }

    public function removeMsGroup(Group $msGroup): self
    {
        if ($this->msGroup->contains($msGroup)) {
            $this->msGroup->removeElement($msGroup);
        }

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

    public function getIcon(): ?Icon
    {
        return $this->icon;
    }

    public function setIcon(?Icon $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
        }

        return $this;
    }
}
