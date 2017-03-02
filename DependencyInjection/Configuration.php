<?php

namespace Netbull\AuthBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

use Netbull\AuthBundle\Model\UserInterface;
use Netbull\AuthBundle\Model\RoleInterface;

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

        $validator = function ( $class, $validateClass ) {
            try {
                $reflection = new \ReflectionClass($class);

                // check whatever class we expect to have
                if ( !$reflection->implementsInterface($validateClass) ) {
                    return true;
                }
            } catch ( \ReflectionException $e ) {
                return true;
            }
            return false;
        };

        $rootNode
            ->children()
                ->scalarNode('user_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('role_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('firewall_name')->isRequired()->cannotBeEmpty()->end()
            ->end()
            ->validate()
                ->ifTrue(function ($v) use ( $validator ) {
                    return $validator($v['user_class'], UserInterface::class);
                })
                ->thenInvalid('You need to specify your own User entity which extends Netbull\\AuthBundle\Model\\UserInterface.')
            ->end()
            ->validate()
                ->ifTrue(function ($v) use ( $validator ) {
                    return $validator($v['role_class'], RoleInterface::class);
                })
                ->thenInvalid('You need to specify your own Role entity which extends Netbull\\AuthBundle\Model\\RoleInterface.')
            ->end()
        ;
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
