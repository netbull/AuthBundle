<?php

namespace NetBull\AuthBundle\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NetBullAuthExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('security.yaml');

        if ($config['listeners']) {
            $loader->load('listeners.yaml');

            $service = $container->getDefinition('netbull_auth.security.forced_logout_listener');
            $service->replaceArgument(5, $config['session']['name']);
            $service->replaceArgument(6, $config['session']['remember_me_name']);
            $service->replaceArgument(7, $config['login_route']);
        }
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return 'netbull_auth';
    }
}
