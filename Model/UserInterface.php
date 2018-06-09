<?php

namespace NetBull\AuthBundle\Model;

/**
 * Interface UserInterface
 * @package NetBull\AuthBundle\Model
 */
interface UserInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     */
    public function setType($type);

    /**
     * @param string $username
     */
    public function setUsername($username);

    /**
     * @return string
     */
    public function getFirstName();

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName);

    /**
     * @return string
     */
    public function getLastName();

    /**
     * @param string $lastName
     */
    public function setLastName($lastName);

    /**
     * @return string
     */
    public function getUsername();

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email);

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail();

    /**
     * @inheritDoc
     */
    public function setPassword($password);

    /**
     * @return mixed
     */
    public function getPlainPassword();

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword);

    /**
     * @inheritdoc
     */
    public function setSalt($salt);

    /**
     * @return string
     */
    public function getLastActive();

    /**
     * @param $lastActive
     * @return $this
     */
    public function setLastActive($lastActive);

    /**
     * @return bool Whether the user is active or not
     */
    public function isActiveNow();

    /**
     * @return boolean
     */
    public function isActive();

    /**
     * @return bool
     */
    public function isForceLogout();

    /**
     * @param bool $forceLogout
     */
    public function setForceLogout($forceLogout);

    /**
     * @param $active
     */
    public function setActive($active);

    /**
     * @param $roles
     */
    public function getRawRoles();

    /**
     * @param $roles
     */
    public function setRawRoles($roles);

    /**
     * @param RoleInterface $role
     * @return $this
     */
    public function addRawRole(RoleInterface $role);

    /**
     * @param RoleInterface $role
     */
    public function removeRawRole(RoleInterface $role);

    ######################################################
    #                                                    #
    #                   Helper Methods                   #
    #                                                    #
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
