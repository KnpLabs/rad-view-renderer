<?php

namespace Knp\Rad\ViewRenderer\Bundle;

use Knp\Rad\ViewRenderer\DependencyInjection\ViewRendererExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ViewRendererBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new ViewRendererExtension();
    }
}
