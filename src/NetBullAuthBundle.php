<?php

namespace NetBull\AuthBundle;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use NetBull\AuthBundle\DependencyInjection\NetBullAuthExtension;

class NetBullAuthBundle extends Bundle
{
    /**
     * @return NetBullAuthExtension|null|ExtensionInterface
     */
    public function getContainerExtension()
    {
        return new NetBullAuthExtension;
    }
}
