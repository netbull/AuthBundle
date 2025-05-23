<?php

namespace NetBull\AuthBundle\Exception;

use Exception;

class NoLoginRouteException extends Exception
{
    public function __construct()
    {
        parent::__construct("There is not login_route specified in the config file.");
    }
}
