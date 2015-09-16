<?php

namespace Knp\Rad\ViewRenderer\Renderer;

use JMS\Serializer\SerializerInterface;
use Knp\Rad\ViewRenderer\Renderer;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class JMSSerializerRenderer implements Renderer
{
    private $serializer;
    private $stack;

    public function __construct(SerializerInterface $serializer, RequestStack $stack)
    {
        $this->serializer = $serializer;
        $this->stack      = $stack;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'jms_serializer';
    }

    /**
     * {@inheritdoc}
     */
    public function supportsContentType($contentType)
    {
        return true === in_array($contentType, [ 'application/json', 'application/xml' ]);
    }

    /**
     * {@inheritdoc}
     */
    public function renderResponse($data = null)
    {
        $format  = $this->getFormat();
        $content = $this->serializer->serialize($data, $format);

        return new Response($content);
    }

    /**
     * @return string
     */
    private function getFormat()
    {
        if (null !== $format = $this->getRequest()->getRequestFormat(null)) {
            return $format;
        }

        $acceptables = $this->getRequest()->getAcceptableContentTypes();

        return end((explode('/', array_shift($acceptables))));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    private function getRequest()
    {
        return $this->stack->getCurrentRequest();
    }
}
