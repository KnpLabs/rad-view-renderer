<?php

namespace Knp\Rad\ViewRenderer\Bundle;

use Knp\Rad\ViewRenderer\DependencyInjection\Compiler\RendererPass;
use Knp\Rad\ViewRenderer\DependencyInjection\ViewRendererExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ViewRendererBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RendererPass());
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new ViewRendererExtension();
    }
}
