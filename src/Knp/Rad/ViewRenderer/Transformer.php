<?php

namespace Knp\Rad\ViewRenderer;

interface Transformer
{
    /**
     * @param array|Symfony\Component\HttpFoundation\Response|null $data
     *
     * @return array|Symfony\Component\HttpFoundation\Response|null
     */
    public function transformResponse($data = null);
}
