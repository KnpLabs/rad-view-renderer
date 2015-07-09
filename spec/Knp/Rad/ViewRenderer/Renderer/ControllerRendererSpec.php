<?php

namespace spec\Knp\Rad\ViewRenderer\Renderer;

use Knp\Rad\ViewRenderer\Controller\Guesser;
use Knp\Rad\ViewRenderer\Renderer\TwigRenderer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ControllerRendererSpec extends ObjectBehavior
{
    function let(RequestStack $stack, TwigRenderer $renderer, Guesser $guesser, Request $request)
    {
        $this->beConstructedWith($stack, $renderer, $guesser);

        $stack->getCurrentRequest()->willReturn($request);
        $stack->getMasterRequest()->willReturn($request);
        $request->getRequestFormat('html')->willReturn('html');
        $guesser->getInformationFromRequest($request)->willReturn(['bundle' => 'Framework', 'controller' => 'Template', 'action' => 'template']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ViewRenderer\Renderer\ControllerRenderer');
    }

    function it_supports_format_of_his_sub_renderer(TwigRenderer $renderer, $result)
    {
        $renderer->supportsContentType('test')->willReturn($result)->shouldBeCalledTimes(1);

        $this->supportsContentType('test')->shouldReturn($result);
    }

    function it_will_render_a_template($renderer, $data, $result, $request)
    {
        $request->isXmlHttpRequest()->willReturn(false);
        $renderer
            ->renderResponse(['template' => 'Framework:Template:template.html.twig', 'parameters' => $data])
            ->willReturn($result)
            ->shouldBeCalledTimes(1)
        ;

        $this->renderResponse($data)->shouldReturn($result);
    }

    function it_will_render_a_subtemplate_if_exists($renderer, $data, $result, $stack, Request $other)
    {
        $stack->getMasterRequest()->willReturn($other);
        $renderer->templateExists('Framework:Template:_template.html.twig')->willReturn(true);

        $renderer
            ->renderResponse(['template' => 'Framework:Template:_template.html.twig', 'parameters' => $data])
            ->willReturn($result)
            ->shouldBeCalledTimes(1)
        ;

        $this->renderResponse($data)->shouldReturn($result);
    }

    function it_will_render_a_subtemplate_if_exists_with_xhr_request($renderer, $data, $result, $stack, $request)
    {
        $request->isXmlHttpRequest()->willReturn(true);
        $renderer->templateExists('Framework:Template:_template.html.twig')->willReturn(true);

        $renderer
            ->renderResponse(['template' => 'Framework:Template:_template.html.twig', 'parameters' => $data])
            ->willReturn($result)
            ->shouldBeCalledTimes(1)
        ;

        $this->renderResponse($data)->shouldReturn($result);
    }

    function it_will_render_a_template_if_subtemplate_doesnt_exists($renderer, $data, $result, $stack, Request $other)
    {
        $stack->getMasterRequest()->willReturn($other);
        $renderer->templateExists('Framework:Template:_template.html.twig')->willReturn(false);

        $renderer
            ->renderResponse(['template' => 'Framework:Template:template.html.twig', 'parameters' => $data])
            ->willReturn($result)
            ->shouldBeCalledTimes(1)
        ;

        $this->renderResponse($data)->shouldReturn($result);
    }
}
