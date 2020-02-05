<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Security\Role;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="User already exists")
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource
 */
class User implements UserInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email();
     * @Assert\NotBlank();
     * @Assert\NotNull();
     * @Groups({"user"})
     */
    private string $email;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $password;

    private $plainPassword;

    /**
     * @ORM\Column(type="json")
     * @Assert\NotNull();
     * @Groups({"user"})
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     * @Groups({"user"})
     */
    private bool $active = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
        // forces the object to look "dirty" to Doctrine. Avoids
        // Doctrine *not* saving this entity, if only plainPassword changes
        $this->password = null;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = Role::USER;

        return array_unique($roles);
    }

    public function hasRole(string $role): bool
    {
        return \in_array($role, $this->getRoles(), true);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return $this->email;
    }
}
