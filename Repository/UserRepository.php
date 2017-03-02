<?php

namespace Netbull\AuthBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class UserRepository
 * @package Netbull\AuthBundle\Repository
 */
abstract class UserRepository extends EntityRepository implements UserLoaderInterface
{
    ##################################################
    #                   Auth Methods                 #
    ##################################################
    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        try {
            return $this->loadUserByUsernameQuery($username)->getOneOrNullResult();
        } catch ( NoResultException $e ) {
            $message = sprintf(
                'Unable to find an active account AuthBundle:User object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message, 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsernameQuery($username)
    {
        return $this->loadUserByUsernameQueryBuilder($username)->getQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsernameQueryBuilder($username)
    {
        $qb =  $this->createQueryBuilder('u');
        return $qb->select('u', 'r')
            ->leftJoin('u.roles', 'r')
            ->where($qb->expr()->orX(
                $qb->expr()->eq('u.email', ':username'),
                $qb->expr()->eq('u.username', ':username')
            ))
            ->setParameter('username', $username)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if ( !$this->supportsClass($class) ) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        $qb = $this->loadUserByUsernameQueryBuilder($user->getUsername());

        $qb->resetDQLPart('where');
        $qb->where($qb->expr()->eq('u.id', ':id'))->setParameter('id', $user->getId());

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $this->getEntityName() === $class
            || is_subclass_of($class, $this->getEntityName());
    }
}
