<?php

namespace NetBull\AuthBundle\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use NetBull\AuthBundle\Model\UserInterface;

/**
 * Class UserRepository
 * @package NetBull\AuthBundle\Repository
 */
class UserRepository extends EntityRepository implements UserLoaderInterface
{
    /**
     * @param UserInterface $user
     * @throws ORMException
     */
    public function save(UserInterface $user)
    {
        $this->_em->persist($user);
        try {
            $this->_em->flush();
        } catch (OptimisticLockException $e) {}
    }

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
        } catch (NonUniqueResultException $e) {
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
            ->leftJoin('u.rawRoles', 'r')
            ->where($qb->expr()->orX(
                $qb->expr()->eq('u.email', ':username'),
                $qb->expr()->eq('u.username', ':username')
            ))
            ->andWhere($qb->expr()->eq('u.active', true))
            ->setParameter('username', $username);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
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

        try {
            return $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $this->getEntityName() === $class
            || is_subclass_of($class, $this->getEntityName());
    }

    /**
     * @param $email
     * @return bool
     */
    public function checkEmailAvailability($email)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select($qb->expr()->count('u'))
            ->from($this->getEntityName(), 'u')
            ->where($qb->expr()->eq('u.email', ':email'))
            ->setParameter('email', $email)
        ;

        try {
            $status = $qb->getQuery()->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return false;
        }

        return (int)$status === 0;
    }

    /**
     * Update Last active of the users
     * @param $ids
     */
    public function updateLastActive($ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        try {
            $now = new DateTime('now');
        } catch (Exception $e) {
            return;
        }

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->update($this->getEntityName(), 'u')
            ->set('u.lastActive', ':time')
            ->where($qb->expr()->in('u.id', ':ids'))
            ->setParameter('ids', $ids)
            ->setParameter('time', $now)
            ->getQuery()->execute();
    }

    /**
     * @param UserInterface $user
     * @return array
     */
    public function getLastActive(UserInterface $user)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->where($qb->expr()->eq('u.active', ':active'))
            ->andWhere($qb->expr()->neq('u.id', ':currentUser'))
            ->orderBy('u.lastActive', 'desc')
            ->setParameters([
                'currentUser' => $user->getId(),
                'active' => true
            ])
        ;

        return $qb->getQuery()->getArrayResult();
    }
}
