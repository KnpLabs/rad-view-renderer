<?php

namespace Knp\Rad\ViewRenderer\EventListener;

use Knp\Rad\ViewRenderer\Renderer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class ViewListener
{
    /**
     * @var array
     */
    private $contentTypes;

    /**
     * @var Renderer[]
     */
    private $renderers;

    public function __construct(array $contentTypes)
    {
        $this->contentTypes = $contentTypes;
        $this->renderers    = [];
    }

    public function addRenderer(Renderer $renderer)
    {
        $this->renderers[$renderer->getName()] = $renderer;
    }

    public function onView(GetResponseForControllerResultEvent $event)
    {
        if (null === $renderer = $this->getRendererFromRequest($event->getRequest())) {
            throw new NotAcceptableHttpException();
        }

        $result = $event->getControllerResult();
        $event->setResponse($renderer->renderResponse($result));
    }

    private function getRendererFromRequest(Request $request)
    {
        if (null === $contentType = $request->attributes->get('_content_type', null)) {
            $contentType = current($request->getAcceptableContentTypes());
        }

        if (null !== $name = $request->attributes->get('_renderer', null)) {
            return $this->getRendererFromName($name);
        }

        if (0 === count($this->contentTypes)) {
            return $this->getRendererFromContentType($contentType);
        }

        if (false === array_key_exists($contentType, $this->contentTypes)) {
            return $this->getRendererFromContentType($contentType);
        }

        return $this->getRendererFromName($this->contentTypes[$contentType]);
    }

    private function getRendererFromContentType($contentType)
    {
        foreach ($this->renderers as $renderer) {
            if ($renderer->supportsContentType($contentType)) {
                return $renderer;
            }
        }

        return null;
    }

    private function getRendererFromName($name)
    {
        if (false === array_key_exists($name, $this->renderers)) {
            throw new \Exception(
                sprintf(
                    'There is no renderer named "%s", "%s" available',
                    $name,
                    implode('", "', array_keys($this->renderers))
                )
            );
        }

        return $this->renderers[$name];
    }
}
