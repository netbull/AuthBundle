<?php

namespace NetBull\AuthBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use NetBull\AuthBundle\Model\RoleInterface;
use NetBull\AuthBundle\Model\UserInterface;
use NetBull\AuthBundle\Repository\RoleRepository;

#[ORM\MappedSuperclass(repositoryClass: RoleRepository::class)]
abstract class Role implements RoleInterface
{
    /**
     * @var RoleInterface|null
     **/
    #[ORM\ManyToOne(targetEntity: RoleInterface::class)]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    protected ?RoleInterface $parent  = null;

    /**
     * @var Collection<UserInterface>
     */
    #[ORM\ManyToMany(targetEntity: UserInterface::class, mappedBy: 'rawRoles')]
    protected Collection $users;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 30)]
    protected ?string $name;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 50, unique: true)]
    protected ?string $role = null;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'group_name', length: 30)]
    protected ?string $group = null;

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
     * @return Collection<UserInterface>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param Collection<UserInterface> $users
     * @return RoleInterface
     */
    public function setUsers(Collection $users): RoleInterface
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
