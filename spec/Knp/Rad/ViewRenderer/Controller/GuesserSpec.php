<?php

namespace spec\Knp\Rad\ViewRenderer\Controller;

use PhpSpec\ObjectBehavior;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerNameParser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class GuesserSpec extends ObjectBehavior
{
    function let(ContainerInterface $container, ControllerNameParser $parser, Request $request, ParameterBag $attributes)
    {
        $this->beConstructedWith($container, $parser);

        $parser->build('Symfony\Bundle\FrameworkBundle\Controller\TemplateController::templateAction')->willReturn('Framework:Template:template');
        $container->get('framework.template')->willReturn(new \Symfony\Bundle\FrameworkBundle\Controller\TemplateController());
        $request->attributes = $attributes;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ViewRenderer\Controller\Guesser');
    }

    function it_parse_controller_name($request, $attributes)
    {
        $attributes->get('_controller', null)->willReturn('Framework:Template:template');

        $this->getInformationFromRequest($request)->shouldReturn([
            'bundle'     => 'Framework',
            'controller' => 'Template',
            'action'     => 'template',
        ]);
    }

    function it_parse_controller_as_a_service_name($request, $attributes)
    {
        $attributes->get('_controller', null)->willReturn('framework.template:templateAction');

        $this->getInformationFromRequest($request)->shouldReturn([
            'bundle'     => 'Framework',
            'controller' => 'Template',
            'action'     => 'template',
        ]);
    }

    function it_parse_controller_with_short_syntax($request, $attributes)
    {
        $attributes->get('_controller', null)->willReturn('Symfony\Bundle\FrameworkBundle\Controller\TemplateController::templateAction');

        $this->getInformationFromRequest($request)->shouldReturn([
            'bundle'     => 'Framework',
            'controller' => 'Template',
            'action'     => 'template',
        ]);
    }

    function it_throw_an_exception_when_bad_format($request, $attributes)
    {
        $attributes->get('_controller', null)->willReturn('Test:::testAction');

        $this
            ->shouldThrow(new \Exception('Can\'t deduce controller, bundle and action names from the given string : "Test:::testAction".'))
            ->duringGetInformationFromRequest($request)
        ;
    }
}
