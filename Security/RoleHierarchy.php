<?php

namespace NetBull\AuthBundle\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Role\RoleHierarchy as BaseRoleHierarchy;
use NetBull\AuthBundle\Model\RoleInterface;

/**
 * Class RoleHierarchy
 * @package NetBull\AuthBundle\Security
 */
class RoleHierarchy extends BaseRoleHierarchy
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var array
     */
    private $roles = [];

    /**
     * RoleHierarchy constructor.
     * @param array $hierarchy
     * @param EntityManager $em
     */
    public function __construct(array $hierarchy, EntityManager $em)
    {
        $this->em = $em;
        parent::__construct($this->buildRolesTree());
    }

    /**
     * @return array
     */
    public function getFlattenRoles()
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
    private function recursiveRoles($groupRoles)
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
    private function buildRolesTree()
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
