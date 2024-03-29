<?php

namespace NetBull\AuthBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use NetBull\AuthBundle\Model\RoleInterface;
use NetBull\AuthBundle\Model\UserInterface;

/**
 * @ORM\MappedSuperclass(repositoryClass="NetBull\AuthBundle\Repository\RoleRepository")
 */
abstract class Role implements RoleInterface
{
    /**
     * @var RoleInterface|null
     *
     * @ORM\ManyToOne(targetEntity="NetBull\AuthBundle\Model\RoleInterface")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    protected $parent;

    /**
     * @var UserInterface[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="NetBull\AuthBundle\Model\UserInterface", mappedBy="rawRoles")
     */
    protected $users;

    /**
     * @var string|null
     *
     * @ORM\Column(length=30)
     */
    protected $name;

    /**
     * @var string|null
     *
     * @ORM\Column(length=50, unique=true)
     */
    protected $role;

    /**
     * @var string|null
     *
     * @ORM\Column(name="group_name", length=30)
     */
    protected $group;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    /**
     * @return RoleInterface|null
     */
    public function getParent(): ?RoleInterface
    {
        return $this->parent;
    }

    /**
     * @param RoleInterface|null $role
     * @return RoleInterface
     */
    public function setParent(?RoleInterface $role): RoleInterface
    {
        $this->parent = $role;

        return $this;
    }

    /**
     * @return ArrayCollection|UserInterface[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param ArrayCollection|UserInterface[] $users
     * @return RoleInterface
     */
    public function setUsers($users): RoleInterface
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return RoleInterface
     */
    public function setName(?string $name): RoleInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param string|null $role
     * @return RoleInterface
     */
    public function setRole(?string $role): RoleInterface
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGroup(): ?string
    {
        return $this->group;
    }

    /**
     * @param string|null $group
     * @return RoleInterface
     */
    public function setGroup(?string $group): RoleInterface
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }
}
