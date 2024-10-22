<?php

namespace NetBull\AuthBundle\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use NetBull\AuthBundle\Model\UserInterface;
use NetBull\AuthBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;
use Serializable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use NetBull\AuthBundle\Model\RoleInterface;

#[UniqueEntity(fields: 'email', message: 'Sorry, this email address is already in use.', entityClass: UserInterface::class)]
#[UniqueEntity(fields: 'username', message: 'Sorry, this username is already in use.', entityClass: UserInterface::class)]
#[ORM\MappedSuperclass(repositoryClass: UserRepository::class)]
abstract class User implements UserInterface, EquatableInterface, Serializable, PasswordAuthenticatedUserInterface
{
    /**
     * @var string|null
     */
    #[ORM\Column(length: 30)]
    protected ?string $type = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 100, nullable: true)]
    protected ?string $username = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 80, nullable: true)]
    protected ?string $firstName = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 80, nullable: true)]
    protected ?string $lastName = null;

    /**
     * @var string|null
     */
    #[Assert\NotBlank(message: 'Please enter a valid email address.')]
    #[Assert\Email(message: 'The provided Email is not valid.')]
    #[ORM\Column]
    protected ?string $email = null;

    /**
     * @var string|null
     */
    #[ORM\Column]
    protected ?string $password = null;

    /**
     * @var string|null
     */
    #[Assert\Length(max: 4096)]
    protected ?string $plainPassword = null;

    /**
     * @var string|null
     */
    #[ORM\Column(nullable: true)]
    protected ?string $salt = null;

    /**
     * @var DateTime|null
     */
    #[ORM\Column(type: 'datetime', nullable: true)]
    protected ?DateTimeInterface $lastActive = null;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    protected bool $active = false;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    protected bool $forceLogout = false;

    /**
     * @var Collection<RoleInterface>
     */
    #[ORM\ManyToMany(targetEntity: RoleInterface::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: 'user_role')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'role_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    protected Collection $rawRoles;

    public function __construct()
    {
        $this->rawRoles = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return UserInterface
     */
    public function setType(?string $type): UserInterface
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     * @return UserInterface
     */
    public function setUsername(?string $username): UserInterface
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     * @return UserInterface
     */
    public function setFirstName(?string $firstName): UserInterface
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     * @return UserInterface
     */
    public function setLastName(?string $lastName): UserInterface
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return UserInterface
     */
    public function setEmail(?string $email): UserInterface
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return UserInterface
     */
    public function setPassword(?string $password): UserInterface
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     * @return UserInterface
     */
    public function setPlainPassword(?string $plainPassword): UserInterface
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * @param string|null $salt
     * @return UserInterface
     */
    public function setSalt(?string $salt): UserInterface
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getLastActive(): ?DateTimeInterface
    {
        return $this->lastActive;
    }

    /**
     * @param DateTimeInterface|null $lastActive
     * @return UserInterface
     */
    public function setLastActive(?DateTimeInterface $lastActive): UserInterface
    {
        $this->lastActive = $lastActive;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return UserInterface
     */
    public function setActive(bool $active): UserInterface
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return bool
     */
    public function isForceLogout(): bool
    {
        return $this->forceLogout;
    }

    /**
     * @param bool $forceLogout
     * @return UserInterface
     */
    public function setForceLogout(bool $forceLogout): UserInterface
    {
        $this->forceLogout = $forceLogout;

        return $this;
    }

    /**
     * @return Collection<RoleInterface>
     */
    public function getRawRoles(): Collection
    {
        return $this->rawRoles;
    }

    /**
     * @param Collection<RoleInterface> $roles
     * @return UserInterface
     */
    public function setRawRoles(Collection $roles): UserInterface
    {
        $this->rawRoles = $roles;

        return $this;
    }

    /**
     * @param RoleInterface $role
     * @return UserInterface
     */
    public function addRawRole(RoleInterface $role): UserInterface
    {
        if (!$this->rawRoles->contains($role)) {
            $this->rawRoles->add($role);
        }

        return $this;
    }

    /**
     * @param RoleInterface $role
     * @return UserInterface
     */
    public function removeRawRole(RoleInterface $role): UserInterface
    {
        if ($this->rawRoles->contains($role)) {
            $this->rawRoles->removeElement($role);
        }

        return $this;
    }

    ######################################################
    #                   Helper Methods                   #
    ######################################################
    /**
    * @return string
    */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @inheritdoc
     */
    public function isActiveNow(): bool
    {
        // Delay during which the user will be considered as still active
        $delay = new DateTime('3 minutes ago');

        return ($this->getLastActive() > $delay);
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->rawRoles->map(function (Role $role) {
            return $role->getRole();
        })->toArray();
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    /**
     * @param BaseUserInterface $user
     * @return bool
     */
    public function isEqualTo(BaseUserInterface $user): bool
    {
        if ($this->getId() !== $user->getId()) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->getEmail() !== $user->getEmail()) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function __serialize(): array
    {
        return [
            $this->id,
            $this->password,
            $this->salt,
            $this->email,
        ];
    }

    /**
     * @internal
     */
    final public function serialize(): string
    {
        return serialize($this->__serialize());
    }

    /**
     * @param array $data
     * @return void
     */
    public function __unserialize(array $data): void
    {
        [$this->id, $this->password, $this->salt, $this->email] = $data;
    }

    /**
     * @internal
     */
    final public function unserialize(string $serialized): void
    {
        $this->__unserialize(unserialize($serialized));
    }

    /**
     * Get the name of the User
     * @return string|null
     */
    public function getName(): ?string
    {
        if (!$this->firstName && !$this->lastName) {
            return null;
        } else if (!$this->firstName) {
            return $this->lastName;
        } else if (!$this->lastName) {
            return $this->firstName;
        } else {
            return $this->firstName . ' ' . $this->lastName;
        }
    }

    /**
     * @return string|null
     */
    public function getInitials(): ?string
    {
        $initials = '';
        if ($this->firstName) {
            $initials .= mb_substr($this->firstName, 0, 1, 'UTF-8');
        }

        if ($this->lastName) {
            $initials .= mb_substr($this->lastName, 0, 1, 'UTF-8');
        }

        return mb_strtoupper($initials, 'UTF-8');
    }
}
