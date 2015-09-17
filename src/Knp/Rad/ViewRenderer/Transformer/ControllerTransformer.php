<?php

namespace Knp\Rad\ViewRenderer\Transformer;

use Knp\Rad\ViewRenderer\Controller\Guesser;
use Knp\Rad\ViewRenderer\Transformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ControllerTransformer implements Transformer
{
    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * @var Guesser
     */
    private $guesser;

    /**
     * @param RequestStack $stack
     * @param Guesser      $guesser
     */
    public function __construct(RequestStack $stack, Guesser $guesser)
    {
        $this->stack   = $stack;
        $this->guesser = $guesser;
    }

    /**
     * {@inheritdoc}
     */
    public function transformResponse($data = null)
    {
        $request  = $this->getRequest();

        if (null === $template) {
            return $data;
        }

        $template = $this->buildTemplate($request);

        return [
            'parameters' => $data,
            'template'   => $template,
        ];
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    private function buildTemplate(Request $request)
    {
        return sprintf(
            '%s.%s.twig',
            implode(':', $this->guesser->getInformationFromRequest($request)),
            $request->getRequestFormat('html')
        );
    }

    /**
     * @return Symfony\Component\HttpFoundation\Request|null
     */
    private function getRequest()
    {
        return $this->stack->getCurrentRequest();
    }
}
