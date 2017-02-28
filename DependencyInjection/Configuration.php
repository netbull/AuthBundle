<?php

namespace Netbull\AuthBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('netbull_auth');

        $this->addProviders($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addProviders( ArrayNodeDefinition $node )
    {
        $node
            ->children()
                ->arrayNode('facebook')
                    ->children()
                        ->scalarNode('id')->defaultNull()->end()
                        ->scalarNode('secret')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
