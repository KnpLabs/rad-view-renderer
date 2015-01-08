<?php

namespace Knp\Rad\ViewRenderer;

use Knp\Rad\ViewRenderer\Renderer;

interface MetaRenderer extends Renderer
{
    /**
     * @param Renderer $renderer
     */
    public function setRenderer(Renderer $renderer);
}
