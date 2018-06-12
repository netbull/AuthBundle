<?php

namespace NetBull\AuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

use NetBull\AuthBundle\DependencyInjection\NetBullAuthExtension;

/**
 * Class NetBullAuthBundle
 * @package NetBull\AuthBundle
 */
class NetBullAuthBundle extends Bundle
{
    /**
     * @return NetBullAuthExtension|null|\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
     */
    public function getContainerExtension()
    {
        return new NetBullAuthExtension();
    }
}
