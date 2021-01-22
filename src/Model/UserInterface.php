<?php

namespace NetBull\AuthBundle\Model;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface as BaseInterface;

/**
 * Interface UserInterface
 * @package NetBull\AuthBundle\Model
 */
interface UserInterface extends BaseInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return string|null
     */
    public function getType(): ?string;

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
    public function getEmail(): ?string;

    /**
     * @param string $username
     */
    public function setUsername(string $username);

    /**
     * @return string|null
     */
    public function getUsername(): ?string;

    /**
     * @return string|null
     */
    public function getFirstName(): ?string;

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName);

    /**
     * @return string|null
     */
    public function getLastName(): ?string;

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
    public function getPlainPassword(): ?string;

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword);

    /**
     * @param string|null $salt
     */
    public function setSalt(?string $salt);

    /**
     * @return DateTime|null
     */
    public function getLastActive(): ?DateTime;

    /**
     * @param DateTime $lastActive
     */
    public function setLastActive(DateTime $lastActive);

    /**
     * @return bool
     */
    public function isActiveNow(): bool;

    /**
     * @return bool
     */
    public function isActive(): bool;

    /**
     * @param bool $active
     */
    public function setActive(bool $active);

    /**
     * @return bool
     */
    public function isForceLogout(): bool;

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
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Get the User Initials
     * @return string|null
     */
    public function getInitials(): ?string;
}
