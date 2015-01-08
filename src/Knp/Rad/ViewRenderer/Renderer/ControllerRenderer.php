<?php

namespace Knp\Rad\ViewRenderer\Renderer;

use Knp\Rad\ViewRenderer\Controller\Guesser as ControllerGuesser;
use Knp\Rad\ViewRenderer\Renderer;
use Knp\Rad\ViewRenderer\Renderer\TwigRenderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ControllerRenderer implements Renderer
{
    /**
     * @var RequestStack $stack
     */
    private $stack;

    /**
     * @var TwigRenderer $renderer
     */
    private $renderer;

    /**
     * @var ControllerRenderer $guesser
     */
    private $guesser;

    /**
     * @param RequestStack $stack
     * @param TwigRenderer $renderer
     * @param ControllerRenderer $guesser
     */
    public function __construct(RequestStack $stack, TwigRenderer $renderer, ControllerGuesser $guesser)
    {
        $this->stack    = $stack;
        $this->renderer = $renderer;
        $this->guesser  = $guesser;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'controller';
    }

    /**
     * {@inheritdoc}
     */
    public function supportsContentType($contentType)
    {
        return $this->renderer->supportsContentType($contentType);
    }

    /**
     * {@inheritdoc}
     */
    public function renderResponse($data = null)
    {
        $request = $this->getRequest();

        $template = $this->buildTemplate($request);

        if (false === $this->isMasterRequest($request)) {
            $other = $this->buildTemplate($request, '_');
            if ($this->renderer->templateExists($other)) {
                $template = $other;
            }
        }

        return $this->renderer->renderResponse(['template' => $template, 'parameters' => $data]);
    }

    /**
     * @param Request $request
     * @param string $prefix
     *
     * @return string
     */
    private function buildTemplate(Request $request, $prefix = '')
    {
        $data           = $this->guesser->getInformationFromRequest($request);
        $data['action'] = sprintf('%s%s', $prefix, $data['action']);

        return sprintf('%s.%s.twig', implode(':', $data), $request->getRequestFormat('html'));
    }

    /**
     * @return Request
     */
    private function getRequest()
    {
        return $this->stack->getCurrentRequest();
    }

    /**
     * @param Request $request
     *
     * @return boolean
     */
    private function isMasterRequest(Request $request)
    {
        return $this->stack->getMasterRequest() === $request;
    }
}
