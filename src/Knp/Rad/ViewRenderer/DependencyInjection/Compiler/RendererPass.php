<?php

namespace Knp\Rad\ViewRenderer\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RendererPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $renderers = $container->findTaggedServiceIds('knp_rad_view_renderer.renderer');
        $listener  = $container->getDefinition('knp_rad_view_renderer.event_listener.view_listener');

        foreach ($renderers as $id => $tags) {
            $listener
                ->addMethodCall('addRenderer', [ new Reference($id) ])
            ;
        }
    }
}
