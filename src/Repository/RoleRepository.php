<?php

namespace NetBull\AuthBundle\Repository;

use Doctrine\ORM\EntityRepository;

class RoleRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getAll(): array
    {
        $qb = $this->createQueryBuilder('r');

        return $qb->select('r', 'p')
            ->leftJoin('r.parent', 'p')
            ->getQuery()->getArrayResult();
    }
}
