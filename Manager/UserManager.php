<?php

namespace Netbull\AuthBundle\Manager;

use Doctrine\ORM\EntityManager;

use Netbull\AuthBundle\Entity\Role;
use Netbull\AuthBundle\Entity\User;
use Netbull\AuthBundle\Model\SocialUser;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class UserManager
 * @package Netbull\AuthBundle\Manager
 */
class UserManager
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var Session
     */
    private $session;

    /**
     * UserManager constructor.
     * @param EntityManager $em
     * @param TokenStorage  $tokenStorage
     * @param Session       $session
     */
    public function __construct( EntityManager $em, TokenStorage $tokenStorage, Session $session )
    {
        $this->em           = $em;
        $this->tokenStorage = $tokenStorage;
        $this->session      = $session;
    }

    /**
     * @param SocialUser $socialUser
     * @param bool $authenticate
     */
    public function mergeOrCreateAccount( SocialUser $socialUser, $authenticate = true )
    {
        $user = $this->em->getRepository(User::class)->findOneBy([ 'email' => $socialUser->getEmail() ]);

        if ( !$user ) {
            $user = new User();
            $user->setEmail($socialUser->getEmail());
            $user->setRoles($this->em->getReference(Role::class, 3));
        }

        if ( !$user->getFirstName() ) {
            $user->setFirstName($socialUser->getFirstName());
        }

        if ( !$user->getLastName() ) {
            $user->setLastName($socialUser->getLastName());
        }

        $user->{'set' . ucfirst($socialUser->getType()) . 'Id'}($socialUser->getId());

        $this->em->persist($user);

        $this->em->flush();

        if ( $authenticate ) {
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->tokenStorage->setToken($token);
            $this->session->set('_security_main', serialize($token));
        }
    }
}
