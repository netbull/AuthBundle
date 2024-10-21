<?php

namespace NetBull\AuthBundle\Security;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchy as BaseRoleHierarchy;
use NetBull\AuthBundle\Model\RoleInterface;

class RoleHierarchy extends BaseRoleHierarchy
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    /**
     * @var array
     */
    private array $roles = [];

    /**
     * @param array $hierarchy
     * @param EntityManagerInterface $em
     */
    public function __construct(array $hierarchy, EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct($this->buildRolesTree());
    }

    /**
     * @return array
     */
    public function getFlattenRoles(): array
    {
        foreach ($this->map as $role => $group) {
            $this->roles[] = $role;

            if (is_array($group)) {
                $this->recursiveRoles($group);
            }
        }

        return $this->roles;
    }

    /**
     * @param $groupRoles
     */
    private function recursiveRoles($groupRoles): void
    {
        foreach ($groupRoles as $role => $group) {
            if (is_array($group)) {
                $this->recursiveRoles($group);
            } else {
                $this->roles[] = $group;
            }
        }
    }

    /**
     * Here we build an array with roles. It looks like a two-levelled tree - just
     * like original Symfony roles are stored in security.yaml
     * @return array
     */
    private function buildRolesTree(): array
    {
        $hierarchy = [];
        $roles = $this->em->createQuery('SELECT r FROM NetBullAuthBundle:Role r')->execute();

        /** @var RoleInterface $role */
        foreach ($roles as $role) {
            if ($role->getParent()) {
                if (!isset($hierarchy[$role->getParent()->getRole()])) {
                    $hierarchy[$role->getParent()->getRole()] = [];
                }
                $hierarchy[$role->getParent()->getRole()][] = $role->getRole();
            } else {
                if (!isset($hierarchy[$role->getRole()])) {
                    $hierarchy[$role->getRole()] = [];
                }
            }
        }

        return $hierarchy;
    }
}
