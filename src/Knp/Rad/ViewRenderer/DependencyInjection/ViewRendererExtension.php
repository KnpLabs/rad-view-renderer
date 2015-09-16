<?php

namespace Knp\Rad\ViewRenderer\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ViewRendererExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $listener = $container->getDefinition('knp_rad_view_renderer.event_listener.view_listener');
        $listener->setArguments([$config['allowed_content_types']]);
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return 'knp_rad_view_renderer';
    }
}
