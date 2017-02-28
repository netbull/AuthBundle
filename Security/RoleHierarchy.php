<?php

namespace Netbull\AuthBundle\Security;

use Doctrine\ORM\EntityManager;

/**
 * Class RoleHierarchy
 * @package Netbull\AuthBundle\Security
 */
class RoleHierarchy extends \Symfony\Component\Security\Core\Role\RoleHierarchy
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * RoleHierarchy constructor.
     * @param array $hierarchy
     * @param EntityManager $em
     */
    public function __construct( array $hierarchy, EntityManager $em )
    {
        parent::__construct($this->buildRolesTree());

        $this->em = $em;
    }

    /**
     * Here we build an array with roles. It looks like a two-levelled tree - just
     * like original Symfony roles are stored in security.yml
     * @return array
     */
    private function buildRolesTree()
    {
        $hierarchy = [];
        $roles = $this->em->createQuery('SELECT role from NetbullAuthBundle:Role role')->execute();
        foreach ( $roles as $role ) {
            if ( $role->getParent() ) {
                if ( !isset($hierarchy[$role->getParent()->getRole()]) ) {
                    $hierarchy[$role->getParent()->getRole()] = [];
                }
                $hierarchy[$role->getParent()->getRole()][] = $role->getRole();
            } else {
                if ( !isset($hierarchy[$role->getRole()]) ) {
                    $hierarchy[$role->getRole()] = [];
                }
            }
        }

        return $hierarchy;
    }
}
