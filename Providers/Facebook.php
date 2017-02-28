<?php

namespace Netbull\AuthBundle\Providers;

use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Session\Session;

use Facebook\Exceptions\FacebookSDKException;
use Facebook\Exceptions\FacebookResponseException;

use Netbull\AuthBundle\Model\SocialUser;
use Netbull\AuthBundle\Manager\UserManager;

/**
 * Class Facebook
 * @package Netbull\AuthBundle\Providers
 */
class Facebook
{
    /**
     * @var string
     */
    private $appId;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var \Facebook\Facebook
     */
    private $instance;

    /**
     * @var \Facebook\Helpers\FacebookRedirectLoginHelper
     */
    private $helper;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var array
     */
    private $permissions = ['email', 'public_profile'];

    /**
     * Facebook constructor.
     * @param string        $id
     * @param string        $secret
     * @param Router        $router
     * @param UserManager   $userManager
     * @throws \Exception
     */
    function __construct( $id, $secret, Router $router, UserManager $userManager )
    {
        if ( empty($id) && empty($secret) ) {
            throw new \Exception('If you want to use this provider configure it in netbull_auth.yml with the Facebook App ID and App Secret');
        }

        $this->router   = $router;
        $this->appId    = $id;
        $this->instance = new \Facebook\Facebook([
            'app_id'                => $id,
            'app_secret'            => $secret,
            'default_graph_version' => 'v2.2',
        ]);

        $this->helper   = $this->instance->getRedirectLoginHelper();
        $this->userManager   = $userManager;
    }

    /**
     * @param string $path
     * @return string
     */
    public function getLoginUrl( $path = 'netbull_auth_facebook_callback' )
    {
        return sprintf('<a href="%s" target="_blank">Login</a>', $this->helper->getLoginUrl(
            $this->router->generate($path, [], Router::ABSOLUTE_URL),
            $this->permissions
        ));
    }

    /**
     *
     */
    public function getToken()
    {
        try {
            $accessToken = $this->helper->getAccessToken();
        } catch( FacebookResponseException $e ) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch( FacebookSDKException $e ) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if ( isset($accessToken) ) {
            $oAuth2Client = $this->instance->getOAuth2Client();
            $tokenMetadata = $oAuth2Client->debugToken($accessToken);

            // If you know the user ID this access token belongs to, you can validate it here
            $tokenMetadata->validateExpiration();

            if ( !$accessToken->isLongLived() ) {
                // Exchanges a short-lived access token for a long-lived one
                try {
                    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                } catch ( FacebookSDKException $e ) {
                    exit;
                }
            }

            try {
                // Returns a `Facebook\FacebookResponse` object
                $response = $this->instance->get('/me?fields=id,email,first_name,last_name', $accessToken);
            } catch( FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch( FacebookSDKException $e) {
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }

            $facebookUser = $response->getGraphUser();

            $socialUser = new SocialUser();
            $socialUser->setFirstName($facebookUser['first_name']);
            $socialUser->setLastName($facebookUser['last_name']);
            $socialUser->setEmail($facebookUser['email']);
            $socialUser->setId($facebookUser['id']);
            $socialUser->setType('facebook');

            $this->userManager->mergeOrCreateAccount($socialUser);
        }
    }
}
