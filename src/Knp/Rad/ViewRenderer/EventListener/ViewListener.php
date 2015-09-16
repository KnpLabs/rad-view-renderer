<?php

namespace Knp\Rad\ViewRenderer\EventListener;

use Knp\Rad\ViewTransformer\Transformer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class ViewListener
{
    /**
     * @var Knp\Rad\ViewTransformer\Transformer[]
     */
    private $transformers;

    public function __construct(array $transformers)
    {
        $this->transformers = $transformers;
    }

    public function onView(GetResponseForControllerResultEvent $event)
    {
        $response = $event->getControllerResult();

        foreach ($this->transformers as $transformer) {
            $response = $transformer->transformResponse($response);
        }

        if (false === $response instanceof Response) {
            throw new \Exception('Fail to generate a response, any of response transformer can build a correct response.');
        }

        $event->setResponse($response);
    }
}
