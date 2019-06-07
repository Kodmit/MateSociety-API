<?php
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;
// todo : Config all this shit
/**
 * @ApiResource(
 *     attributes={
 *         "access_control"="is_granted('ROLE_USER')"
 *     },
 *     collectionOperations={
 *         "get",
 *         "post"
 *     },
 *     itemOperations={
 *         "get",
 *         "put"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"},
 *         "delete"={"access_control"="is_granted('ROLE_ADMIN') or object.creator == user"}
 *     },
 *     normalizationContext={"groups"={"read_group_goal"}},
 *     denormalizationContext={"groups"={"write_group_goal"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\GroupGoalRepository")
 * @ORM\Table(name="group_goals")
 */
class GroupGoal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Group", inversedBy="groupGoals")
     * @ORM\JoinColumn(nullable=false)
     * @ApiSubresource
     */
    private $_group;

    /**
     * @ORM\Column(type="string", length=200)
     * @Groups({"read_group_goal", "write_group_goal", "read_group"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read_group_goal", "write_group_goal", "read_group"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read_group_goal"})
     */
    private $created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Icon", inversedBy="groupGoal")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read_group_goal", "write_group_goal", "read_group"})
     */
    private $icon;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    public $creator;

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

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }
}
