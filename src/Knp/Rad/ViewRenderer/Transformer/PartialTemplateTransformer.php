<?php

namespace Knp\Rad\ViewRenderer\Transformer;

use Knp\Rad\ViewRenderer\Transformer;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PartialTemplateTransformer implements Transformer
{
    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(RequestStack $stack, EngineInterface $templating)
    {
        $this->stack      = $stack;
        $this->templating = $templating;
    }

    /**
     * {@inheritdoc}
     */
    public function transformResponse($data = null)
    {
        if (false === is_array($data)) {
            return $data;
        }

        if (false === array_key_exists('template', $data)) {
            return $data;
        }

        if (false === array_key_exists('parameters', $data)) {
            return $data;
        }

        if (true === $this->inMasterRequest()) {
            return $data;
        }

        if (true === $this->templating->exists($data['template'])) {
            return $data;
        }

        $parts   = explode(':', $data['template']);
        $folders = explode('/', array_pop($parts));

        $file = array_pop($folders);

        $folders[] = sprintf('_%s', $file);
        $parts[]   = implode('/', $folders);

        $data['template'] = implode(':', $parts);

        return $data;
    }

    /**
     * @return bool
     */
    private function inMasterRequest()
    {
        return $this->stack->getMasterRequest() === $this->stack->getCurrentRequest();
    }
}
