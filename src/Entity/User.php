<?php

namespace NetBull\AuthBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Serializable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use NetBull\AuthBundle\Model\RoleInterface;
use NetBull\AuthBundle\Model\UserInterface as BaseInterface;

/**
 * @UniqueEntity(fields="email", message="Sorry, this email address is already in use.", entityClass="NetBull\AuthBundle\Model\UserInterface")
 * @UniqueEntity(fields="username", message="Sorry, this username is already in use.", entityClass="NetBull\AuthBundle\Model\UserInterface")
 *
 * @ORM\MappedSuperclass(repositoryClass="NetBull\AuthBundle\Repository\UserRepository")
 */
abstract class User implements BaseInterface, UserInterface, EquatableInterface, Serializable
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

    /**
     * User constructor.
     */
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
     * @return User
     */
    public function setType(?string $type): User
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
     * @return User
     */
    public function setUsername(?string $username): User
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
     * @return User
     */
    public function setFirstName(?string $firstName): User
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
     * @return User
     */
    public function setLastName(?string $lastName): User
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
     * @return User
     */
    public function setEmail(?string $email): User
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
     * @return User
     */
    public function setPassword(?string $password): User
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
     * @return User
     */
    public function setPlainPassword(?string $plainPassword): User
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
     * @return User
     */
    public function setSalt(?string $salt): User
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
     * @return User
     */
    public function setLastActive(?DateTime $lastActive): User
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
     * @return User
     */
    public function setActive(bool $active): User
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
     * @return User
     */
    public function setForceLogout(bool $forceLogout): User
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
     * @return User
     */
    public function setRawRoles($roles): User
    {
        $this->rawRoles = $roles;

        return $this;
    }

    /**
     * @param RoleInterface $role
     * @return $this
     */
    public function addRawRole(RoleInterface $role): User
    {
        if (!$this->rawRoles->contains($role)) {
            $this->rawRoles->add($role);
        }

        return $this;
    }

    /**
     * @param RoleInterface $role
     * @return $this
     */
    public function removeRawRole(RoleInterface $role): User
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
     * @inheritdoc
     */
    public function isActiveNow(): bool
    {
        // Delay during which the user will be considered as still active
        $delay = new DateTime('3 minutes ago');

        return ($this->getLastActive() > $delay);
    }

    /**
     * @inheritdoc
     */
    public function getRoles()
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
     * @param UserInterface $user
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        if ($this->id !== $user->getId()) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->email !== $user->getEmail()) {
            return false;
        }

        return true;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->password,
            $this->salt,
            $this->email,
        ]);
    }

    /**
     * @see \Serializable::unserialize()
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->password,
            $this->salt,
            $this->email,
        ) = unserialize($serialized);
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
