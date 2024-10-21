<?php

namespace NetBull\AuthBundle\Model;

use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface as BaseInterface;

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
     * @return DateTimeInterface|null
     */
    public function getLastActive(): ?DateTimeInterface;

    /**
     * @param DateTimeInterface $lastActive
     */
    public function setLastActive(DateTimeInterface $lastActive);

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
     * @return Collection<RoleInterface>
     */
    public function getRawRoles(): Collection;

    /**
     * @param Collection $roles
     */
    public function setRawRoles(Collection $roles);

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
     * @param string $data
     * @see \Serializable::unserialize()
     */
    public function unserialize(string $data);

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
