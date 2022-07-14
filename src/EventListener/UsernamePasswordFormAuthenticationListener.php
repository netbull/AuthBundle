<?php

namespace NetBull\AuthBundle\EventListener;

use NetBull\AuthBundle\Model\UserInterface;
use NetBull\AuthBundle\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Firewall\UsernamePasswordFormAuthenticationListener as BaseClass;

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
