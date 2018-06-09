<?php

namespace NetBull\AuthBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class RoleRepository
 * @package NetBull\AuthBundle\Repository
 */
class RoleRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getAll()
    {
        $qb = $this->createQueryBuilder('r');

        return $qb->select('r', 'p')
            ->leftJoin('r.parent', 'p')
            ->getQuery()->getArrayResult();
    }
}
