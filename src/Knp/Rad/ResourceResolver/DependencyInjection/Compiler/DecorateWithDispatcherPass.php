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
            $decorated = (new Definition('%'.$this->decoratorClassParam.'%'))
                ->setDecoratedService($this->decoratedService)
                ->setArguments([
                    new Reference($this->decoratedService.'.with_dispatcher.inner'),
                    new Reference('event_dispatcher'),
                ])
            ;

            $container->setDefinition($this->decoratedService.'.with_dispatcher', $decorated);
        }
    }
}
