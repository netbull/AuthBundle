<?php

namespace Netbull\AuthBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Role
 * @package Netbull\AuthBundle\Model
 *
 * @ORM\MappedSuperclass()
 */
abstract class Role extends \Symfony\Component\Security\Core\Role\Role implements RoleInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Netbull\AuthBundle\Model\RoleInterface")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    protected $parent;

    /**
     * @ORM\ManyToMany(targetEntity="Netbull\AuthBundle\Model\UserInterface", mappedBy="roles")
     */
    protected $users;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=30)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=50, unique=true)
     */
    protected $role;

    /**
     * @var int
     *
     * @ORM\Column(name="group", type="integer")
     */
    protected $group;

    /**
     * Role constructor..
     */
    public function __construct()
    {
        parent::__construct($this->role);
        $this->users = new ArrayCollection();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Role $role
     *
     * @return Role
     */
    public function setParent(Role $role)
    {
        $this->parent = $role;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param $users
     *
     * @return Role
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @param UserInterface $user
     */
    public function addUser(UserInterface $user)
    {
        if ( !$this->users->contains($user) ) {
            $this->users->add($user);
        }
    }

    /**
     * @param UserInterface $user
     *
     * @return Role
     */
    public function removeUser(UserInterface $user)
    {
        if ( $this->users->contains($user) ) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    /**
     * @param $name
     *
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param $role
     *
     * @return Role
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param $group
     *
     * @return Role
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString() {
        return $this->name;
    }
}
