<?php

namespace NetBull\AuthBundle\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use NetBull\AuthBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface, PasswordUpgraderInterface
{
    /**
     * @param UserInterface $user
     */
    public function save(UserInterface $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    ##################################################
    #                   Auth Methods                 #
    ##################################################
    /**
     * @param $username
     * @return UserInterface|null
     *
     * @deprecated since Symfony 5.3, use loadUserByIdentifier() instead
     */
    public function loadUserByUsername($username): ?UserInterface
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
     * @param $username
     * @return Query
     */
    public function loadUserByUsernameQuery($username): Query
    {
        return $this->loadUserByUsernameQueryBuilder($username)->getQuery();
    }

    /**
     * @param $username
     * @return QueryBuilder
     */
    public function loadUserByUsernameQueryBuilder($username): QueryBuilder
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
     * @param UserInterface $user
     * @return float|int|mixed|string|null
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
     * @param $class
     * @return bool
     */
    public function supportsClass($class): bool
    {
        return $this->getEntityName() === $class
            || is_subclass_of($class, $this->getEntityName());
    }

    /**
     * @param string $email
     * @return bool
     */
    public function checkEmailAvailability(string $email): bool
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select($qb->expr()->countDistinct('u'))
            ->where($qb->expr()->eq('u.email', ':email'))
            ->setParameter('email', $email);

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
    public function getLastActive(UserInterface $user): array
    {
        $qb = $this->createQueryBuilder('u');
        $qb->where($qb->expr()->eq('u.active', ':active'))
            ->andWhere($qb->expr()->neq('u.id', ':currentUser'))
            ->orderBy('u.lastActive', 'desc')
            ->setParameters([
                'currentUser' => $user->getId(),
                'active' => true
            ]);

        return $qb->getQuery()->getArrayResult();
    }

    /**
     * @param string $identifier
     * @return UserInterface|null
     */
    public function loadUserByIdentifier(string $identifier): ?UserInterface
    {
        try {
            return $this->loadUserByUsernameQuery($identifier)->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            $message = sprintf(
                'Unable to find an active account AuthBundle:User object identified by "%s".',
                $identifier
            );
            throw new UserNotFoundException($message, 0, $e);
        }
    }

    /**
     * @param UserInterface $user
     * @param string $newHashedPassword
     */
    public function upgradePassword(UserInterface $user, string $newHashedPassword)
    {
        $em = $this->getEntityManager();
        $user->setPassword($newHashedPassword);
        $em->persist($user);
        $em->flush();
    }
}
