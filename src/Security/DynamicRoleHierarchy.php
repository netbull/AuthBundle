<?php

namespace NetBull\AuthBundle\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use NetBull\AuthBundle\Model\RoleInterface;
use NetBull\AuthBundle\Repository\RoleRepository;

class DynamicRoleHierarchy implements RoleHierarchyInterface
{
    /**
     * @var RoleRepository $roleRepository
     */
    protected $roleRepository;

    /**
     * @var null
     */
    protected $roleHierarchy = null;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->roleRepository = $em->getRepository(RoleInterface::class);
    }

    /**
     * @param array $roles
     * @return array|string[]
     */
    public function getReachableRoleNames(array $roles): array
    {
        if (null === $this->roleHierarchy) {
            $this->roleHierarchy = new RoleHierarchy($this->fetchRoleHierarchy());
        }

        return $this->roleHierarchy->getReachableRoleNames($roles);
    }

    /**
     * @return array
     */
    protected function fetchRoleHierarchy(): array
    {
        $hierarchy = [];

        $roles = $this->roleRepository->getAll();
        foreach ($roles as $role) {
            if ($role['parent']) {
                $parentRole = $role['parent']['role'];
                if (!isset($hierarchy[$parentRole])) {
                    $hierarchy[$parentRole] = [];
                }
                $hierarchy[$parentRole][] = $role['role'];
            } else {
                if (!isset($hierarchy[$role['role']])) {
                    $hierarchy[$role['role']] = [];
                }
            }
        }

        return $hierarchy;
    }
}
