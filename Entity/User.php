<?php

namespace NetBull\AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use NetBull\AuthBundle\Model\RoleInterface;
use NetBull\AuthBundle\Model\UserInterface as BaseInterface;

/**
 * User
 *
 * @UniqueEntity(fields="email", message="Sorry, this email address is already in use.", entityClass="NetBull\AuthBundle\Model\UserInterface")
 * @UniqueEntity(fields="username", message="Sorry, this username is already in use.", entityClass="NetBull\AuthBundle\Model\UserInterface")
 *
 * @ORM\MappedSuperclass(repositoryClass="NetBull\AuthBundle\Repository\UserRepository")
 */
class User implements BaseInterface, UserInterface, EquatableInterface, \Serializable
{
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    protected $lastName;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Please enter a valid email address.")
     * @Assert\Email(message="The provided Email is not valid.")
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    protected $password;

    /**
     * @Assert\Length(max=4096)
     */
    protected $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    protected $salt;

    /**
     * @var \DateTime
     * @ORM\Column(name="last_active", type="datetime")
     */
    protected $lastActive;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    protected $active = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="force_logout", type="boolean")
     */
    protected $forceLogout = false;

    /**
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
    function __construct()
    {
        $this->rawRoles = new ArrayCollection();

        if ($this->lastActive == null) {
            $this->lastActive = new \DateTime('now');
        }
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @inheritdoc
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritdoc
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @inheritdoc
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @inheritdoc
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @inheritdoc
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @inheritdoc
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @inheritdoc
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @inheritdoc
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @inheritdoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritdoc
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @inheritdoc
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @inheritdoc
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @inheritdoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritdoc
     */
    public function getLastActive()
    {
        return $this->lastActive;
    }

    /**
     * @inheritdoc
     */
    public function setLastActive($lastActive)
    {
        $this->lastActive = $lastActive;
    }

    /**
     * @inheritdoc
     */
    public function isActiveNow()
    {
        // Delay during which the user will be considered as still active
        $delay = new \DateTime('3 minutes ago');

        return ($this->getLastActive() > $delay);
    }

    /**
     * @inheritdoc
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @inheritdoc
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return bool
     */
    public function isForceLogout()
    {
        return $this->forceLogout;
    }

    /**
     * @param bool $forceLogout
     */
    public function setForceLogout($forceLogout)
    {
        $this->forceLogout = $forceLogout;
    }

    /**
     * @inheritdoc
     */
    public function getRoles()
    {
        $roles = $this->rawRoles->map(function (Role $role) {
            return $role->getRole();
        })->toArray();

        return $roles;
    }

    /**
     * @inheritdoc
     */
    public function getRawRoles()
    {
        return $this->rawRoles;
    }

    /**
     * @inheritdoc
     */
    public function setRawRoles($roles)
    {
        $this->rawRoles = $roles;
    }

    /**
     * @inheritdoc
     */
    public function addRawRole(RoleInterface $role)
    {
        if (!$this->rawRoles->contains($role)) {
            $this->rawRoles->add($role);
        }
    }

    /**
     * @inheritdoc
     */
    public function removeRawRole(RoleInterface $role)
    {
        if ($this->rawRoles->contains($role)) {
            $this->rawRoles->removeElement($role);
        }
    }

    ######################################################
    #                                                    #
    #                   Helper Methods                   #
    #                                                    #
    ######################################################

    /**
     * @inheritdoc
     */
    public function eraseCredentials() {}

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
     * @return null|string
     */
    public function getName()
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
     * Get the User Initials
     * @return mixed|string
     */
    public function getInitials()
    {
        $initials = '';
        if ($this->firstName) {
            $initials .= substr($this->firstName, 0, 1);
        }

        if ($this->lastName) {
            $initials .= substr($this->lastName, 0, 1);
        }

        return mb_strtoupper($initials, 'UTF-8');
    }
}