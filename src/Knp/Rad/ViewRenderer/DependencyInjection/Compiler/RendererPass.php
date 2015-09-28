<?php

namespace Knp\Rad\ViewRenderer\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class RendererPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->disableJmsSerializerRenderer($container);

        $nativeEnabled = $container->getParameter('knp_rad_view_renderer.enabled_native_renderers');
        $renderers = $container->findTaggedServiceIds('knp_rad_view_renderer.renderer');
        $listener  = $container->getDefinition('knp_rad_view_renderer.event_listener.view_listener');

        foreach ($renderers as $id => $tags) {
            $rendererName = str_replace('_renderer', '', array_pop((explode('.', $id))));

            if (false !== strpos($id, 'knp_rad_view_renderer.renderer.') && !in_array($rendererName, $nativeEnabled)) {
                $container->removeDefinition($id);

                continue;
            }

            $listener
                ->addMethodCall('addRenderer', [ new Reference($id) ])
            ;
        }
    }

    private function disableJmsSerializerRenderer(ContainerBuilder $container)
    {
        if ($container->has('jms_serializer')) {
            $container
                ->getDefinition('knp_rad_view_renderer.renderer.rest_renderer')
                ->addMethodCall('setRenderer', [new Reference('knp_rad_view_renderer.renderer.jms_serializer_renderer')])
            ;

            $container->setDefinition(
                'knp_rad_view_renderer.renderer.jms_serializer_renderer',
                (new Definition('Knp\Rad\ViewRenderer\Renderer\JMSSerializerRenderer'))
                    ->setArguments([new Reference('jms_serializer'), new Reference('request_stack')])
                    ->addTag('knp_rad_view_renderer.renderer')
            );
        }
    }
}
