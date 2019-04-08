<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={
 *          "access_control"="is_granted('ROLE_USER')",
 *          "normalization_context"={"groups"={"read_user"}},
 *          "denormalization_context"={"groups"={"write_user"}}
 *     },
 *     collectionOperations={
 *         "get",
 *         "post"={"validation_groups"={"Default", "postValidation"}}
 *     },
 *     itemOperations={
 *         "delete"={"access_control"="is_granted('ROLE_ADMIN')"},
 *         "get",
 *         "put"={"access_control"="is_granted('ROLE_USER')"},
 *         "enable_user"={
 *              "denormalization_context"={"groups"={"write_enable"}},
 *              "controller"=App\Controller\EnableUser::class,
 *              "path"="/users/{id}/enable/{token}",
 *              "method"="GET",
 *              "swagger_context" = {
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
 *          }
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 2,
     *      max = 50
     * )
     * @Groups({"read_user", "write_user"})
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

    public function __construct()
    {
        $this->roles = [];
        array_push($this->roles, "ROLE_USER");
        array_push($this->roles, "ROLE_WRITER");
        $this->enabled = false;
        $this->token = sha1(rand(1, 999));
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
}
