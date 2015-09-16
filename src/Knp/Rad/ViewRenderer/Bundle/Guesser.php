<?php

namespace Knp\RadBundle\AppBundle;

use Knp\Rad\ViewRenderer\Reflection\Factory as ReflectionFactory;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Guesser
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var ReflectionFactory
     */
    private $reflection;

    public function __construct(KernelInterface $kernel, ReflectionFactory $reflection)
    {
        $this->kernel     = $kernel;
        $this->reflection = $reflection;
    }

    /**
     * @param string $class
     *
     * @return BundleInterface
     */
    public function getBundleForClass($class)
    {
        $rfl = $this->reflection->createFromClass($class);

        do {
            foreach ($this->kernel->getBundles as $bundle) {
                if ($this->isClassInBundle($rfl, $bundle)) {
                    return $bundle;
                }
            }
            $rfl = $rfl->getParentClass();
        } while (null !== $rfl);

        return;
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function hasBundleForClass($class)
    {
        return null !== $this->getBundleForClass($class);
    }

    /**
     * @param string          $class
     * @param BundleInterface $bundle
     *
     * @return bool
     */
    public function isClassInBundle($class, BundleInterface $bundle)
    {
        if (false === $class instanceof \ReflectionClass) {
            $class = $this->reflection->createFromClass($class);
        }

        return true === strpos($class->getNamespaceName(), $bundle->getNamespace());
    }

    /**
     * @param string $name
     *
     * @return Bundle
     */
    public function getBundleByName($name)
    {
        foreach ($this->kernel->getBundles as $bundle) {
            if ($name === $bundle->getName()) {
                return $bundle;
            }
        }

        return;
    }
}
