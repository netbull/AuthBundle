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
     * @return string|null
     */
    public function getId();

    /**
     * @param RoleInterface $role
     * @return mixed
     */
    public function setParent(RoleInterface $role);

    /**
     * @return RoleInterface|null
     */
    public function getParent();

    /**
     * @return ArrayCollection
     */
    public function getUsers();

    /**
     * @param UserInterface[]|ArrayCollection $users
     */
    public function setUsers($users);

    /**
     * @param string $name
     */
    public function setName(string $name);

    /**
     * @return string|null
     */
    public function getName();

    /**
     * @return string|null
     */
    public function getRole();

    /**
     * @param string $role
     */
    public function setRole(string $role);

    /**
     * @return string|null
     */
    public function getGroup();

    /**
     * @param string $group
     */
    public function setGroup(string $group);
}
