<?php

namespace Knp\Rad\ViewRenderer\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class Guesser
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ControllerNameParser
     */
    private $parser;

    public function __construct(ContainerInterface $container, ControllerNameParser $parser)
    {
        $this->container = $container;
        $this->parser    = $parser;
    }

    /**
     * @param Request $request
     *
     * @return String[]|null
     */
    public function getInformationFromRequest(Request $request)
    {
        if (null === $attribute = $request->attributes->get('_controller', null)) {
            return;
        }

        if (2 === count($data = explode(':', $attribute))) {
            list($service, $method) = $data;
            $instance               = $this->container->get($service);
            $attribute              = sprintf('%s::%s', get_class($instance), $method);
        }

        if (1 === preg_match('/\w+::\w+/', $attribute)) {
            $attribute = $this->parser->build($attribute);
        }

        if (3 === count($data = explode(':', $attribute))) {
            return array_combine(['bundle', 'controller', 'action'], $data);
        }

        throw new \Exception(sprintf(
            'Can\'t deduce controller, bundle and action names from the given string : "%s".',
            $request->attributes->get('_controller')
        ));
    }
}
