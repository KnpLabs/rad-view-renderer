<?php

namespace Knp\Rad\ViewRenderer\Controller;

use Symfony\Component\HttpFoundation\RequestStack;

class TemplateRenderer
{
    /**
     * @var RequestStack
     */
    private $stack;

    public function __construct(RequestStack $stack)
    {
        $this->stack = $stack;
    }

    public function __invoke()
    {
        $request    = $this->stack->getCurrentRequest();
        $attributes = $request->attributes;

        if (false === $attributes->has('_template')) {
            throw new \RuntimeException('No template configuration found, please add a default "_template" to your route configuration');
        }

        return [
            'template'   => $attributes->get('_template'),
            'parameters' => $attributes->all(),
        ];
    }
}
