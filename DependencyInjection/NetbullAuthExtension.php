<?php

namespace Netbull\AuthBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class NetbullAuthExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load( array $configs, ContainerBuilder $container )
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->configureProviders($container, $config);
    }

    /**
     * @param ContainerBuilder  $container
     * @param array             $config
     */
    private function configureProviders( ContainerBuilder $container, array $config )
    {
        if ( $container->hasDefinition('netbull_auth.provider.facebook') && !empty($config['facebook']['id']) && !empty($config['facebook']['secret']) ) {
            $container->getDefinition('netbull_auth.provider.facebook')
                ->replaceArgument(0, $config['facebook']['id'])
                ->replaceArgument(1, $config['facebook']['secret'])
            ;
        } else {
            $container->removeDefinition('netbull_auth.provider.facebook');
        }
    }
}
