<?php

namespace NetBull\AuthBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Interface RoleInterface
 * @package NetBull\AuthBundle\Model
 */
interface RoleInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @param RoleInterface $role
     * @return mixed
     */
    public function setParent(RoleInterface $role);

    /**
     * @return RoleInterface|null
     */
    public function getParent(): ?RoleInterface;

    /**
     * @return ArrayCollection|UserInterface[]
     */
    public function getUsers();

    /**
     * @param ArrayCollection|UserInterface[] $users
     */
    public function setUsers($users);

    /**
     * @param string $name
     */
    public function setName(string $name);

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return string|null
     */
    public function getRole(): ?string;

    /**
     * @param string $role
     */
    public function setRole(string $role);

    /**
     * @return string|null
     */
    public function getGroup(): ?string;

    /**
     * @param string $group
     */
    public function setGroup(string $group);
}
