<?php

namespace Knp\Rad\ResourceResolver\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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
     * @param string $container
     * @param string $tag
     */
    public function __construct($container, $tag)
    {
        $this->container = $container;
        $this->tag       = $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $registry = $container->findDefinition($this->container);

        foreach ($container->findTaggedServiceIds($this->tag) as $casterId => $casterTags) {
            $registry->addMethodCall('addCaster', [new Reference($casterId)]);
        }
    }
}
