<?php

namespace NetBull\AuthBundle;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use NetBull\AuthBundle\DependencyInjection\NetBullAuthExtension;

class NetBullAuthBundle extends Bundle
{
    /**
     * @return ExtensionInterface|null
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new NetBullAuthExtension;
    }
}
