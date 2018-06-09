<?php

namespace NetBull\AuthBundle\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

use NetBull\AuthBundle\Model\UserInterface;

/**
 * Class ForcedLogoutListener
 * @package NetBull\AuthBundle\Security
 */
class ForcedLogoutListener
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authChecker;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var string
     */
    protected $sessionName;

    /**
     * @var string
     */
    protected $rememberMeSessionName;

    /**
     * @var string
     */
    protected $loginRoute;

    /**
     * ForcedLogoutListener constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param AuthorizationCheckerInterface $authChecker
     * @param SessionInterface $session
     * @param RouterInterface $router
     * @param EntityManagerInterface $em
     * @param string $sessionName
     * @param string $rememberMeSessionName
     * @param string $loginRoute
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authChecker,
        SessionInterface $session,
        RouterInterface $router,
        EntityManagerInterface $em,
        string $sessionName,
        string $rememberMeSessionName,
        string $loginRoute
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->authChecker = $authChecker;
        $this->session = $session;
        $this->router = $router;
        $this->em = $em;
        $this->sessionName = $sessionName;
        $this->rememberMeSessionName = $rememberMeSessionName;
        $this->loginRoute = $loginRoute;
    }

    /**
     * @param GetResponseEvent $event
     *
     * @return RedirectResponse|void
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest() || !$this->isUserLoggedIn()) {
            return;
        }

        $accessToken = $this->tokenStorage->getToken();

        /** @var UserInterface $user */
        $user = $accessToken->getUser();

        // Forcing user to log out if required.
        if ($user->isForceLogout()) {
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
    protected function isUserLoggedIn()
    {
        try {
            return $this->authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED');
        } catch (AuthenticationCredentialsNotFoundException $exception) {
            // Ignoring this exception.
        }
        return false;
    }

    /**
     * @param string $routeName
     *
     * @return RedirectResponse
     */
    protected function getRedirectResponse($routeName)
    {
        return new RedirectResponse(
            $this->router->generate($routeName)
        );
    }

    /**
     * @param Response $response
     */
    protected function logUserOut(Response $response = null)
    {
        // Logging user out.
        $this->tokenStorage->setToken(null);

        // Invalidating the session.
        $this->session->invalidate();

        // Clearing the cookies.
        if (null !== $response) {
            foreach ([ $this->sessionName, $this->rememberMeSessionName ] as $cookieName) {
                $response->headers->clearCookie($cookieName);
            }
        }
    }
}