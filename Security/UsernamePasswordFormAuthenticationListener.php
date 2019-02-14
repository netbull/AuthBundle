<?php

namespace NetBull\AuthBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use NetBull\AuthBundle\Repository\UserRepository;
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
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function setUserRepository(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return null|Response|TokenInterface
     * @throws \Doctrine\ORM\ORMException
     */
    protected function attemptAuthentication(Request $request)
    {
        $result = parent::attemptAuthentication($request);

        if ($result instanceof TokenInterface) {
            /** @var UserInterface $user */
            $user = $result->getUser();

            if ($user instanceof UserInterface && $user->isForceLogout()) {
                // Clearing this flag.
                $user->setForceLogout(false);
                $this->userRepository->save($user);
            }
        }

        return $result;
    }
}
