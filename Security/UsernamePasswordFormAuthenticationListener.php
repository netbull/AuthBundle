<?php

namespace NetBull\AuthBundle\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Firewall\UsernamePasswordFormAuthenticationListener as BaseClass;

use NetBull\AuthBundle\Model\UserInterface;

/**
 * Class UsernamePasswordFormAuthenticationListener
 * @package NetBull\AuthBundle\Security
 */
class UsernamePasswordFormAuthenticationListener extends BaseClass
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Request $request
     *
     * @return null|Response|TokenInterface
     */
    protected function attemptAuthentication(Request $request)
    {
        $result = parent::attemptAuthentication($request);

        if ($result instanceof TokenInterface) {
            /** @var UserInterface $user */
            $user = $result->getUser();

            if ($user->isForceLogout()) {
                // Clearing this flag.
                $user->setForceLogout(false);
                $this->em->persist($user);
                $this->em->flush();
            }
        }

        return $result;
    }
}