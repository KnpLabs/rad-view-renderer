<?php

namespace Knp\Rad\ViewRenderer\Transformer;

use Knp\Rad\ViewRenderer\Transformer;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class TwigTransformer implements Transformer
{
    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @param EngineInterface $templating
     */
    public function __construct(EngineInterface $templating)
    {
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

        return $this->templating->renderResponse($data['template'], $data['parameters']);
    }
}
