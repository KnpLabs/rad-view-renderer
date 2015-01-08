<?php

namespace Knp\Rad\ViewRenderer;

interface Renderer
{
    /**
     * @param string $contentType
     *
     * @return boolean
     */
    public function supportsContentType($contentType);

    /**
     * Description
     *
     * @param mixed $options
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderResponse($data = null);

    /**
     * @return string
     */
    public function getName();
}
