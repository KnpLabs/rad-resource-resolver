<?php

namespace Knp\Rad\ResourceResolver\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DisableIfServicesUnavailable implements CompilerPassInterface
{
    /**
     * @var string|string[]
     */
    private $required;

    /**
     * @var string
     */
    private $dependant;

    /**
     * @param string|string[] $required
     * @param string          $dependant
     */
    public function __construct($required, $dependant)
    {
        $this->required  = $required;
        $this->dependant = $dependant;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $available = true;

        foreach ($this->required AS $required) {
            if (!$container->has($required)) {
                $available = false;
                break;
            }
        }

        if (!$available && $container->has($this->dependant)) {
            $container->removeDefinition($this->dependant);
        }
    }
}
