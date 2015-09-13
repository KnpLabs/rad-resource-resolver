<?php

namespace Knp\Rad\ResourceResolver\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class DecorateWithDispatcherPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private $decoratedService;

    /**
     * @var string
     */
    private $decoratorClassParam;

    /**
     * @param string $decoratedService
     * @param string $decoratorClassParam
     */
    public function __construct($decoratedService, $decoratorClassParam)
    {
        $this->decoratedService    = $decoratedService;
        $this->decoratorClassParam = $decoratorClassParam;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('event_dispatcher')) {
            $decoratorClass = $container->getParameter($this->decoratorClassParam);

            $decorated = (new Definition($decoratorClass))
                ->setDecoratedService($this->decoratedService)
                ->setArguments([
                    new Reference('event_dispatcher'),
                    new Reference('knp_rad_resource_resolver.resource.container.bag.inner')
                ])
            ;

            $container->setDefinition($this->decoratedService, $decorated);
        }
    }
}
