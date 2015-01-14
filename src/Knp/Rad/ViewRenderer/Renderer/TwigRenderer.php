<?php

namespace Knp\Rad\ViewRenderer\Renderer;

use Knp\Rad\ViewRenderer\Renderer;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class TwigRenderer implements Renderer
{
    /**
     * @var EngineInterface $templating
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
    public function getName()
    {
        return 'twig';
    }

    /**
     * {@inheritdoc}
     */
    public function supportsContentType($contentType)
    {
        return in_array($contentType, [ null, 'text/html' ]);
    }

    /**
     * {@inheritdoc}
     */
    public function renderResponse($data = null)
    {
        if (false === is_array($data)) {
            throw new \InvalidArgumentException('Data should be an array');
        }

        if (false === array_key_exists('template', $data)) {
            throw new \InvalidArgumentException('Data should contain a "template" key');
        }

        if (false === array_key_exists('parameters', $data)) {
            throw new \InvalidArgumentException('Data should contain a "parameters" key');
        }

        return $this->templating->renderResponse($data['template'], $data['parameters']);
    }

    /**
     * @param string $name
     *
     * @return boolean
     */
    public function templateExists($name)
    {
        return $this->templating->exists($name);
    }
}
