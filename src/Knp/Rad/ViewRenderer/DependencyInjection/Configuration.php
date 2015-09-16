<?php

namespace Knp\Rad\ViewRenderer\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('knp_rad_view_renderer');

        $rootNode
            ->children()
                ->arrayNode('renderers')
                    ->defaultValue(['controller', 'jms_serializer', 'rest', 'twig'])
                    ->prototype('scalar')
                        ->validate()
                        ->ifNotInArray(['controller', 'jms_serializer', 'rest', 'twig'])
                            ->thenInvalid('Invalid renderer "%s".')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
