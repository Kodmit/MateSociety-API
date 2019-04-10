<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource(
 *     attributes={
 *          "normalization_context"={"groups"={"read_user"}},
 *          "denormalization_context"={"groups"={"write_user"}}
 *     },
 *     collectionOperations={
 *         "get"={"access_control"="is_granted('ROLE_ADMIN')"},
 *         "post"
 *     },
 *     itemOperations={
 *         "delete"={"access_control"="is_granted('ROLE_ADMIN')"},
 *         "get"={"access_control"="is_granted('ROLE_ADMIN') or object == user"},
 *         "put"={"access_control"="is_granted('ROLE_ADMIN') or object == user"},
 *         "enable_user"={
 *              "denormalization_context"={"groups"={"write_enable"}},
 *              "controller"=App\Controller\EnableUser::class,
 *              "path"="/users/{id}/enable/{token}",
 *              "method"="GET",
 *              "swagger_context" = {
 *                  "summary" = "Enable the user account",
 *                  "parameters" = {
 *                      {
 *                          "name" = "token",
 *                          "in" = "path",
 *                          "description" = "User Token to activate his account.",
 *                          "required" = "true",
 *                          "type" : "string",
 *                      },
 *                      {
 *                          "name" = "id",
 *                          "in" = "path",
 *                          "description" = "User ID.",
 *                          "required" = "true",
 *                          "type" : "integer",
 *                      }
 *                  }
 *               }
 *          },
 *          "login"={
 *              "route_name"="api_login_check",
 *              "method"="POST",
 *               "swagger_context" = {
 *                  "parameters" = {
 *                      {
 *                          "name" = "body",
 *                          "in" = "body",
 *                          "required" = "false",
 *                          "description" = "The JSON string to authenticate the user",
 *                          "example"={
 *                              "username"="xxxx",
 *                              "password"="xxxx"
 *                          }
 *                      }
 *                  },
 *                  "responses" = {
 *                      "200" = {
 *                          "description" = "JWT Token is returned",
 *                      },
 *                      "400" = {
 *                          "description" = "Invalid input"
 *                      },
 *                      "401" = {
 *                          "description" = "Bad credentials"
 *                      }
 *                  },
 *                  "summary" = "Send user credentials and retrieve JWT Token",
 *                  "consumes" = {
 *                      "application/json"
 *                   },
 *                  "produces" = {
 *                      "application/json"
 *                   }
 *              }
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=190, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 2,
     *      max = 50
     * )
     * @Groups({"read_user", "write_user", "read_group"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @var $plainPassword
     * @Assert\Length(
     *      min = 6,
     *      minMessage = "Your password must be at least {{ limit }} characters long"
     * )
     * @Groups({"write_user"})
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="array")
     * @Groups({"read_user"})
     */
    private $roles = [];

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read_user"})
     */
    private $enabled;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"write_enable", "read_user"})
     */
    private $token;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read_user"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=190, unique=true)
     * @Assert\Email
     * @Groups({"write_user", "read_user"})
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"write_user", "read_user"})
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     * @Groups({"write_user", "read_user"})
     */
    private $city;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"write_user", "read_user"})
     */
    private $description;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"write_user", "read_user"})
     */
    private $birthday;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Group", mappedBy="creator", cascade={"persist", "remove"})
     */
    private $owned_group;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\News", mappedBy="creator")
     */
    private $news;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupFeed", mappedBy="creator")
     */
    private $groupFeeds;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\GroupFeedComment", mappedBy="creator")
     */
    private $groupFeedComments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Report", mappedBy="creator", orphanRemoval=true)
     */
    private $reports;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\JoinRequest", mappedBy="creator", orphanRemoval=true)
     */
    private $joinRequests;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Group", inversedBy="users")
     */
    private $group_member;


    public function __construct()
    {
        $this->roles = [];
        array_push($this->roles, "ROLE_USER");
        array_push($this->roles, "ROLE_WRITER");
        $this->enabled = false;
        $this->token = sha1(rand(1, 999));
        $this->created_at = new \DateTime();
        $this->news = new ArrayCollection();
        $this->groupFeeds = new ArrayCollection();
        $this->groupFeedComments = new ArrayCollection();
        $this->reports = new ArrayCollection();
        $this->joinRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function setPlainPassword(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function setCity(?string $city): self
    {
        $this->city = $city;

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

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getOwnedGroup(): ?Group
    {
        return $this->owned_group;
    }

    public function setOwnedGroup(Group $owned_group): self
    {
        $this->owned_group = $owned_group;

        // set the owning side of the relation if necessary
        if ($this !== $owned_group->getCreator()) {
            $owned_group->setCreator($this);
        }

        return $this;
    }

    /**
     * @return Collection|News[]
     */
    public function getNews(): Collection
    {
        return $this->news;
    }

    public function addNews(News $news): self
    {
        if (!$this->news->contains($news)) {
            $this->news[] = $news;
            $news->setCreator($this);
        }

        return $this;
    }

    public function removeNews(News $news): self
    {
        if ($this->news->contains($news)) {
            $this->news->removeElement($news);
            // set the owning side to null (unless already changed)
            if ($news->getCreator() === $this) {
                $news->setCreator(null);
            }
        }

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
            $groupFeed->setCreator($this);
        }

        return $this;
    }

    public function removeGroupFeed(GroupFeed $groupFeed): self
    {
        if ($this->groupFeeds->contains($groupFeed)) {
            $this->groupFeeds->removeElement($groupFeed);
            // set the owning side to null (unless already changed)
            if ($groupFeed->getCreator() === $this) {
                $groupFeed->setCreator(null);
            }
        }

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
            $groupFeedComment->setCreator($this);
        }

        return $this;
    }

    public function removeGroupFeedComment(GroupFeedComment $groupFeedComment): self
    {
        if ($this->groupFeedComments->contains($groupFeedComment)) {
            $this->groupFeedComments->removeElement($groupFeedComment);
            // set the owning side to null (unless already changed)
            if ($groupFeedComment->getCreator() === $this) {
                $groupFeedComment->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Report[]
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setCreator($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->contains($report)) {
            $this->reports->removeElement($report);
            // set the owning side to null (unless already changed)
            if ($report->getCreator() === $this) {
                $report->setCreator(null);
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
            $joinRequest->setCreator($this);
        }

        return $this;
    }

    public function removeJoinRequest(JoinRequest $joinRequest): self
    {
        if ($this->joinRequests->contains($joinRequest)) {
            $this->joinRequests->removeElement($joinRequest);
            // set the owning side to null (unless already changed)
            if ($joinRequest->getCreator() === $this) {
                $joinRequest->setCreator(null);
            }
        }

        return $this;
    }

    public function getGroupMember(): ?Group
    {
        return $this->group_member;
    }

    public function setGroupMember(?Group $group_member): self
    {
        $this->group_member = $group_member;

        return $this;
    }
}
