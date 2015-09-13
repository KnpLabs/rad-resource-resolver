<?php

namespace Knp\Rad\ResourceResolver\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class BuildSpecificContainerPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $container;

    /**
     * @var string
     */
    private $tag;

    /**
     * @var string
     */
    private $addMethod;

    /**
     * @param string $container
     * @param string $tag
     * @param string $addMethod
     */
    public function __construct($container, $tag, $addMethod)
    {
        $this->container = $container;
        $this->tag       = $tag;
        $this->addMethod = $addMethod;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $registry = $container->findDefinition($this->container);

        foreach ($container->findTaggedServiceIds($this->tag) as $casterId => $casterTags) {
            $registry->addMethodCall($this->addMethod, [new Reference($casterId)]);
        }
    }
}
