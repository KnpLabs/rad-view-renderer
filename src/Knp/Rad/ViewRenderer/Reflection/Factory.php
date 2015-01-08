<?php

namespace Knp\Rad\ViewRenderer\Reflection;

class Factory
{
    /**
     * @param string $class
     *
     * @return \ReflectionClass
     */
    public function createFromClass($class)
    {
        return new \ReflectionClass($class);
    }
}
