<?php

namespace NetBull\AuthBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('netbull_auth');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->booleanNode('listeners')->defaultTrue()->end()
                ->scalarNode('login_route')->defaultNull()->end()
                ->arrayNode('session')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')->defaultValue('SESS')->end()
                        ->scalarNode('remember_me_name')->defaultValue('LONGSESS')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
