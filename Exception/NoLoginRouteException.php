<?php

namespace NetBull\AuthBundle\Exception;

/**
 * Class NoLoginRouteException
 * @package NetBull\AuthBundle\Exception
 */
class NoLoginRouteException extends \Exception
{
    /**
     * NoLoginRouteException constructor.
     */
    public function __construct()
    {
        parent::__construct("There is not login_route specified in the config file.");
    }
}
