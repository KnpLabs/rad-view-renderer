<?php

namespace Knp\Rad\ViewRenderer\Renderer;

use Knp\Rad\ViewRenderer\MetaRenderer;
use Knp\Rad\ViewRenderer\Renderer;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class RestRenderer implements MetaRenderer
{
    /** @var RequestStack */
    private $stack;

    /** @var Renderer */
    private $renderer;

    private $codes = [
        'POST'   => Response::HTTP_CREATED,
        'GET'    => Response::HTTP_OK,
        'PATCH'  => Response::HTTP_OK,
        'PUT'    => Response::HTTP_OK,
        'DELETE' => Response::HTTP_OK,
    ];

    public function __construct(RequestStack $stack)
    {
        $this->stack = $stack;
    }

    public function setRenderer(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getName()
    {
        return 'rest';
    }

    public function supportsContentType($contentType)
    {
        return $this->renderer->supportsContentType($contentType);
    }

    public function renderResponse($data = null)
    {
        $response = $this->renderer->renderResponse($data);

        $method = strtoupper($this->getRequest()->getMethod());

        if (true === array_key_exists($method, $this->codes)) {
            $statusCode = $this->codes[$method];
            $statusCode = (null === $data && Response::HTTP_OK === $statusCode)
                ? Response::HTTP_NO_CONTENT
                : $statusCode
            ;

            $response->setStatusCode($statusCode, Response::$statusText[$statusCode]);
        }

        return $response;
    }

    private function getRequest()
    {
        return $this->stack->getCurrentRequest();
    }
}
