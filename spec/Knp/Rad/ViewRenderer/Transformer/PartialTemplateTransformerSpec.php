<?php

namespace spec\Knp\Rad\ViewRenderer\Transformer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PartialTemplateTransformerSpec extends ObjectBehavior
{
    function let(RequestStack $stack, EngineInterface $templating, Request $request)
    {
        $this->beConstructedWith($stack, $templating);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ViewRenderer\Transformer\PartialTemplateTransformer');
    }

    function it_doesnt_transform_data_if_its_not_an_array($object)
    {
        $this->transformResponse($object)->shouldReturn($object);
        $this->transformResponse('string')->shouldReturn('string');
        $this->transformResponse(true)->shouldReturn(true);
        $this->transformResponse(null)->shouldReturn(null);
        $this->transformResponse(42)->shouldReturn(42);
    }

    function it_doesnt_transform_data_array_doesnt_contains_template_or_parameters()
    {
        $this->transformResponse([])->shouldReturn([]);
        $this->transformResponse(['template' => 'foo'])->shouldReturn(['template' => 'foo']);
        $this->transformResponse(['parameters' => ['bar', 'baz']])->shouldReturn(['parameters' => ['bar', 'baz']]);
    }

    function it_doesnt_transform_data_if_we_are_on_a_master_request($stack, $request)
    {
        $stack->getCurrentRequest()->willReturn($request);
        $stack->getMasterRequest()->willReturn($request);

        $this
            ->transformResponse([
                'template' => 'App:Resource:template.html.twig',
                'parameters' => ['bar', 'baz'],
            ])
            ->shouldReturn([
                'template' => 'App:Resource:template.html.twig',
                'parameters' => ['bar', 'baz'],
            ])
        ;
    }

    function it_doesnt_transform_data_if_the_template_exists($stack, $request, $other, $templating)
    {
        $stack->getCurrentRequest()->willReturn($request);
        $stack->getMasterRequest()->willReturn($other);

        $templating->exists('App:Resource:template.html.twig')->willReturn(true);

        $this
            ->transformResponse([
                'template' => 'App:Resource:template.html.twig',
                'parameters' => ['bar', 'baz'],
            ])
            ->shouldReturn([
                'template' => 'App:Resource:template.html.twig',
                'parameters' => ['bar', 'baz'],
            ])
        ;
    }

    function it_transforms_data_if_the_template_doesnt_exists($stack, $request, $other, $templating)
    {
        $stack->getCurrentRequest()->willReturn($request);
        $stack->getMasterRequest()->willReturn($other);

        $templating->exists('App:Resource:template.html.twig')->willReturn(false);

        $this
            ->transformResponse([
                'template' => 'App:Resource:template.html.twig',
                'parameters' => ['bar', 'baz'],
            ])
            ->shouldReturn([
                'template' => 'App:Resource:_template.html.twig',
                'parameters' => ['bar', 'baz'],
            ])
        ;
    }
}
