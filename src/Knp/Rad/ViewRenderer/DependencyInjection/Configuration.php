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

        $types = [
            'html' => 'text/html',
            'json' => 'application/json',
        ];

        $rootNode
            ->children()
                ->arrayNode('allowed_content_types')
                    ->defaultValue(array_values($types))
                    ->prototype('scalar')
                        ->beforeNormalization()
                            ->ifTrue(function ($allowedType) use ($types) { return isset($types[$allowedType]); })
                                ->then(function ($allowedType) use ($types) { return $types[$allowedType]; })
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
