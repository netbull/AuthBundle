<?php

namespace NetBull\AuthBundle\Model;

use Doctrine\Common\Collections\Collection;

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
    public function setParent(RoleInterface $role): mixed;

    /**
     * @return RoleInterface|null
     */
    public function getParent(): ?RoleInterface;

    /**
     * @return Collection<UserInterface>
     */
    public function getUsers(): Collection;

    /**
     * @param Collection<UserInterface> $users
     */
    public function setUsers(Collection $users);

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
