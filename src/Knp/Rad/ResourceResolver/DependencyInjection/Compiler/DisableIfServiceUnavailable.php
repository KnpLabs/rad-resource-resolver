<?php

namespace Knp\Rad\ResourceResolver\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DisableIfServiceUnavailable implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $required;

    /**
     * @var string
     */
    private $dependant;

    /**
     * @param string $required
     * @param string $dependant
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
        if (!$container->has($this->required) && $container->has($this->dependant)) {
            if ($container->hasAlias($this->dependant)) {
                $container->removeAlias($this->dependant);
            } else {
                $container->removeDefinition($this->dependant);
            }
        }
    }
}
