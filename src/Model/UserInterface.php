<?php

namespace NetBull\AuthBundle\Model;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Interface UserInterface
 * @package NetBull\AuthBundle\Model
 */
interface UserInterface
{
    /**
     * @return int|null
     */
    public function getId();

    /**
     * @return string|null
     */
    public function getType();

    /**
     * @param string $type
     */
    public function setType(string $type);

    /**
     * @param string $email
     */
    public function setEmail(string $email);

    /**
     * @return string|null
     */
    public function getEmail();

    /**
     * @param string $username
     */
    public function setUsername(string $username);

    /**
     * @return string|null
     */
    public function getUsername();

    /**
     * @return string|null
     */
    public function getFirstName();

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName);

    /**
     * @return string|null
     */
    public function getLastName();

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName);

    /**
     * @param string $password
     */
    public function setPassword(string $password);

    /**
     * @return string|null
     */
    public function getPlainPassword();

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword);

    /**
     * @param string|null $salt
     */
    public function setSalt(?string $salt);

    /**
     * @return string
     */
    public function getLastActive();

    /**
     * @param DateTime $lastActive
     */
    public function setLastActive(DateTime $lastActive);

    /**
     * @return bool
     */
    public function isActiveNow();

    /**
     * @return bool
     */
    public function isActive();

    /**
     * @param bool $active
     */
    public function setActive(bool $active);

    /**
     * @return bool
     */
    public function isForceLogout();

    /**
     * @param bool $forceLogout
     */
    public function setForceLogout(bool $forceLogout);

    /**
     * @return ArrayCollection|RoleInterface[]
     */
    public function getRawRoles();

    /**
     * @param $roles
     */
    public function setRawRoles($roles);

    /**
     * @param RoleInterface $role
     */
    public function addRawRole(RoleInterface $role);

    /**
     * @param RoleInterface $role
     */
    public function removeRawRole(RoleInterface $role);

    ######################################################
    #                   Helper Methods                   #
    ######################################################

    /**
     * @see \Serializable::serialize()
     */
    public function serialize();

    /**
     * @see \Serializable::unserialize()
     * @param string $serialized
     */
    public function unserialize($serialized);

    /**
     * Get the name of the User
     * @return null|string
     */
    public function getName();

    /**
     * Get the User Initials
     * @return mixed|string
     */
    public function getInitials();
}
