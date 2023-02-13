<?php

namespace NetBull\AuthBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use NetBull\AuthBundle\Model\UserInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;
use Serializable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use NetBull\AuthBundle\Model\RoleInterface;

/**
 * @UniqueEntity(fields="email", message="Sorry, this email address is already in use.", entityClass="NetBull\AuthBundle\Model\UserInterface")
 * @UniqueEntity(fields="username", message="Sorry, this username is already in use.", entityClass="NetBull\AuthBundle\Model\UserInterface")
 *
 * @ORM\MappedSuperclass(repositoryClass="NetBull\AuthBundle\Repository\UserRepository")
 */
abstract class User implements UserInterface, EquatableInterface, Serializable
{
    /**
     * @var string|null
     *
     * @ORM\Column(length=30)
     */
    protected $type;

    /**
     * @var string|null
     *
     * @ORM\Column(length=100, nullable=true)
     */
    protected $username;

    /**
     * @var string|null
     *
     * @ORM\Column(length=80, nullable=true)
     */
    protected $firstName;

    /**
     * @var string|null
     *
     * @ORM\Column(length=80, nullable=true)
     */
    protected $lastName;

    /**
     * @var string|null
     *
     * @Assert\NotBlank(message="Please enter a valid email address.")
     * @Assert\Email(message="The provided Email is not valid.")
     *
     * @ORM\Column
     */
    protected $email;

    /**
     * @var string|null
     *
     * @ORM\Column
     */
    protected $password;

    /**
     * @var string|null
     *
     * @Assert\Length(max=4096)
     */
    protected $plainPassword;

    /**
     * @var string|null
     *
     * @ORM\Column(nullable=true)
     */
    protected $salt;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastActive;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $active = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $forceLogout = false;

    /**
     * @var RoleInterface[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="NetBull\AuthBundle\Model\RoleInterface", inversedBy="users")
     * @ORM\JoinTable(name="user_role",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id", onDelete="CASCADE")}
     *  )
     */
    protected $rawRoles;

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
     * @return DateTime|null
     */
    public function getLastActive(): ?DateTime
    {
        return $this->lastActive;
    }

    /**
     * @param DateTime|null $lastActive
     * @return UserInterface
     */
    public function setLastActive(?DateTime $lastActive): UserInterface
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
     * @return ArrayCollection|RoleInterface[]
     */
    public function getRawRoles()
    {
        return $this->rawRoles;
    }

    /**
     * @param ArrayCollection|RoleInterface[] $roles
     * @return UserInterface
     */
    public function setRawRoles($roles): UserInterface
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
     * @return array|string[]
     */
    public function getRoles(): array
    {
        return $this->rawRoles->map(function (Role $role) {
            return $role->getRole();
        })->toArray();
    }

    public function eraseCredentials()
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
     * @see \Serializable::serialize()
     */
    public function serialize(): ?string
    {
        return serialize([
            $this->id,
            $this->password,
            $this->salt,
            $this->email,
        ]);
    }

    /**
     * @param string $data
     */
    public function unserialize($data)
    {
        list (
            $this->id,
            $this->password,
            $this->salt,
            $this->email,
        ) = unserialize($data);
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
