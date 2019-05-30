<?php

namespace NetBull\AuthBundle\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

use NetBull\AuthBundle\Model\RoleInterface;
use NetBull\AuthBundle\Repository\RoleRepository;

/**
 * Class DynamicRoleHierarchy
 * @package NetBull\AuthBundle\Security
 */
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
     * DynamicRoleHierarchy constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->roleRepository = $em->getRepository(RoleInterface::class);
    }

    /**
     * @inheritdoc
     */
    public function getReachableRoles(array $roles)
    {
        if (0 === \func_num_args() || func_get_arg(0)) {
            @trigger_error(sprintf('The %s() method is deprecated since Symfony 4.3 and will be removed in 5.0. Use roles as strings and the getReachableRoleNames() method instead.', __METHOD__), E_USER_DEPRECATED);
        }

        if (null === $this->roleHierarchy) {
            $this->roleHierarchy = new RoleHierarchy($this->fetchRoleHierarchy());
        }

        return $this->roleHierarchy->getReachableRoles($roles);
    }

    /**
     * @inheritdoc
     */
    public function getReachableRoleNames(array $roles)
    {
        if (null === $this->roleHierarchy) {
            $this->roleHierarchy = new RoleHierarchy($this->fetchRoleHierarchy());
        }

        return $this->roleHierarchy->getReachableRoleNames($roles);
    }

    /**
     * @return array
     */
    protected function fetchRoleHierarchy()
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
