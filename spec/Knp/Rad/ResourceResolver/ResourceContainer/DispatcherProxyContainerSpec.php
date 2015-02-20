<?php

namespace spec\Knp\Rad\ResourceResolver\ResourceContainer;

use Knp\Rad\ResourceResolver\Events;
use Knp\Rad\ResourceResolver\ResourceContainer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DispatcherProxyContainerSpec extends ObjectBehavior
{
    function let(ResourceContainer $container, EventDispatcherInterface $dispatcher)
    {
        $this->beConstructedWith($container, $dispatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ResourceResolver\ResourceContainer\DispatcherProxyContainer');
    }

    function it_dispatch_an_event_when_a_resource_is_added($container, $dispatcher, $resource)
    {
        $dispatcher
            ->dispatch(Events::RESOURCES_ADDED, Argument::type('Knp\Rad\ResourceResolver\Event\ResourceEvent\ResourceEvent'))
            ->shouldBeCalled()
        ;
        $container->addResource('key', $resource)->shouldBeCalled();

        $this->addResource('key', $resource);
    }

    function it_dispatch_an_event_when_a_resource_is_removed($container, $dispatcher, $resource)
    {
        $dispatcher
            ->dispatch(Events::RESOURCES_REMOVED, Argument::type('Knp\Rad\ResourceResolver\Event\ResourceEvent\ResourceEvent'))
            ->shouldBeCalled()
        ;
        $container->removeResource('key')->shouldBeCalled();
        $container->getResource('key')->willReturn($resource);

        $this->removeResource('key');
    }
}
