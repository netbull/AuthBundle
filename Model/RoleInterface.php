<?php

namespace Netbull\AuthBundle\Model;

/**
 * Interface RoleInterface
 * @package Netbull\AuthBundle\Model
 */
interface RoleInterface
{
    /**
     * @inheritdoc
     */
    public function getId();

    /**
     * @param Role $role
     * @return $this
     */
    public function setParent(Role $role);

    /**
     * @return mixed
     */
    public function getParent();

    /**
     * @return mixed
     */
    public function getUsers();

    /**
     * @param $users
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
     * @param $name
     * @return Role
     */
    public function setName($name);

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @return mixed
     */
    public function getRole();

    /**
     * @param $role
     * @return Role
     */
    public function setRole($role);

    /**
     * @return mixed
     */
    public function getGroup();

    /**
     * @param $group
     * @return Role
     */
    public function setGroup($group);
}