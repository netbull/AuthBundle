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
     * @inheritdoc
     */
    public function getId();

    /**
     * @param RoleInterface $role
     * @return $this
     */
    public function setParent(RoleInterface $role);

    /**
     * @return RoleInterface
     */
    public function getParent();

    /**
     * @return ArrayCollection
     */
    public function getUsers();

    /**
     * @param ArrayCollection $users
     */
    public function setUsers($users);

    /**
     * @param UserInterface $user
     */
    public function addUser(UserInterface $user);

    /**
     * @param UserInterface $user
     */
    public function removeUser(UserInterface $user);

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getRole();

    /**
     * @param string $role
     */
    public function setRole($role);

    /**
     * @return string
     */
    public function getGroup();

    /**
     * @param string $group
     */
    public function setGroup($group);
}