<?php

namespace Netbull\AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Netbull\AuthBundle\Repository\UserRepository")
 *
 * @UniqueEntity(fields="email", message="Email адресът е вече зает")
 * @UniqueEntity(fields="username", message="Потребителското име е вече заето")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=60)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=60)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=25, unique=true)
     * @Assert\NotBlank(message="Моля въведи потребителско име")
     */
    private $username;

    /**
     * @Assert\NotBlank(message="Моля въведи Парола")
     * @Assert\Length(min=7, max=4096, minMessage="Паролата трябва да е над 7 символа", maxMessage="Паролата трябва да е под 4096 символа")
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank(message="Моля въведи email адрес")
     * @Assert\Email(message="Email адресът трябва да е реален")
     */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = true;

    /**
     * @var \DateTime
     * @ORM\Column(name="last_active", type="datetime")
     */
    private $last_active;

    /**
     * @ORM\ManyToMany(targetEntity="Netbull\AuthBundle\Entity\Role", inversedBy="users")
     */
    private $roles;

    /**
     * @var integer
     *
     * @ORM\Column(name="facebook_id", type="integer", length=17)
     */
    private $facebookId;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->active   = true;
        $this->roles    = new ArrayCollection();

        if ( null == $this->last_active ){
            $this->last_active = new \DateTime('now');
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getLastActive()
    {
        return $this->last_active;
    }

    /**
     * @param mixed $last_active
     */
    public function setLastActive($last_active)
    {
        $this->last_active = $last_active;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @param Role $role
     */
    public function addRole(Role $role)
    {
        if ( !$this->roles->contains($role) ) {
            $this->roles->add($role);
            $role->addUser($this);
        }
    }

    /**
     * @param Role $role
     */
    public function removeRole(Role $role)
    {
        if ( $this->roles->contains($role) ) {
            $this->roles->removeElement($role);
        }
    }

    /**
     * @return int
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param int $facebookId
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
    }

    ##########################################
    #             Helper Methods             #
    ##########################################

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials(){ }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password
            ) = unserialize($serialized);
    }
}
