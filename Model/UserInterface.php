<?php

namespace Netbull\AuthBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

/**
 * Interface UserInterface
 * @package Netbull\AuthBundle\Model
 */
interface UserInterface extends BaseUserInterface, \Serializable
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return string
     */
    public function getFirstName();

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName);

    /**
     * @return mixed
     */
    public function getLastName();

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName);

    /**
     * @return mixed
     */
    public function getUsername();

    /**
     * @param mixed $username
     */
    public function setUsername($username);

    /**
     * @return mixed
     */
    public function getPlainPassword();

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword);

    /**
     * @return mixed
     */
    public function getPassword();

    /**
     * @param mixed $password
     */
    public function setPassword($password);

    /**
     * @return mixed
     */
    public function getEmail();

    /**
     * @param mixed $email
     */
    public function setEmail($email);

    /**
     * @return bool
     */
    public function isActive();

    /**
     * @param bool $active
     */
    public function setActive($active);

    /**
     * @return mixed
     */
    public function getLastActive();

    /**
     * @param mixed $last_active
     */
    public function setLastActive($last_active);

    /**
     * @return mixed
     */
    public function getRoles();

    /**
     * @param mixed $roles
     */
    public function setRoles($roles);

    /**
     * @param RoleInterface $role
     */
    public function addRole(RoleInterface $role);

    /**
     * @param RoleInterface $role
     */
    public function removeRole(RoleInterface $role);

    /**
     * @return int
     */
    public function getFacebookId();

    /**
     * @param int $facebookId
     */
    public function setFacebookId($facebookId);
}
