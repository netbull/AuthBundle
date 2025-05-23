<?php

namespace NetBull\AuthBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use NetBull\AuthBundle\Exception\NoLoginRouteException;
use NetBull\AuthBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class ForcedLogoutListener
{
    /**
     * @param TokenStorageInterface $tokenStorage
     * @param AuthorizationCheckerInterface $authChecker
     * @param RequestStack $requestStack
     * @param RouterInterface $router
     * @param EntityManagerInterface $em
     * @param string $sessionName
     * @param string $rememberMeSessionName
     * @param string $loginRoute
     */
    public function __construct(
        protected TokenStorageInterface $tokenStorage,
        protected AuthorizationCheckerInterface $authChecker,
        protected RequestStack $requestStack,
        protected RouterInterface $router,
        protected EntityManagerInterface $em,
        protected string $sessionName,
        protected string $rememberMeSessionName,
        protected string $loginRoute
    )
    {
    }

    /**
     * @param RequestEvent $event
     * @throws NoLoginRouteException
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest() || !$this->isUserLoggedIn()) {
            return;
        }

        $accessToken = $this->tokenStorage->getToken();

        /** @var UserInterface $user */
        $user = $accessToken->getUser();

        // Forcing user to log out if required.
        if ($user instanceof UserInterface && $user->isForceLogout()) {
            if (!$this->loginRoute) {
                throw new NoLoginRouteException;
            }

            // Logging user out.
            $response = $this->getRedirectResponse($this->loginRoute);
            $this->logUserOut($response);

            // Saving the user.
            $user->setForceLogout(false);
            $this->em->persist($user);
            $this->em->flush();

            // Setting redirect response.
            $event->setResponse($response);
        }
    }

    /**
     * @return bool
     */
    protected function isUserLoggedIn(): bool
    {
        try {
            return $this->authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED');
        } catch (AuthenticationCredentialsNotFoundException) {
            // Ignoring this exception.
        }
        return false;
    }

    /**
     * @param string $routeName
     *
     * @return RedirectResponse
     */
    protected function getRedirectResponse(string $routeName): RedirectResponse
    {
        return new RedirectResponse($this->router->generate($routeName));
    }

    /**
     * @param Response|null $response
     */
    protected function logUserOut(Response $response = null): void
    {
        // Logging user out.
        $this->tokenStorage->setToken();

        // Invalidating the session.
        try {
            $session = $this->requestStack->getSession();
            $session->invalidate();
        } catch (SessionNotFoundException $e) {
        }

        // Clearing the cookies.
        if (null !== $response) {
            foreach ([$this->sessionName, $this->rememberMeSessionName] as $cookieName) {
                $response->headers->clearCookie($cookieName);
            }
        }
    }
}
