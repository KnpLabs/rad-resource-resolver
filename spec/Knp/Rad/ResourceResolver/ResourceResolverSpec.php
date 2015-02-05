<?php

namespace spec\Knp\Rad\ResourceResolver;

use Knp\Rad\ResourceResolver\Events;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Route;

class ResourceResolverSpec extends ObjectBehavior
{
    function let(ContainerInterface $container, Route $route, $reference)
    {
        $this->beConstructedWith($container);

        $container->get('@app.my.route')->willReturn($route);
        $route->setOption('"myFirstParameter"', true)->willReturn($reference);

    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ResourceResolver\ResourceResolver');
    }

    function it_resolves_a_resource($reference)
    {
        $this
            ->resolveResource('@app.my.route', 'setOption', ['"myFirstParameter"', true])
            ->shouldReturn($reference)
        ;
    }

    function it_dispatch_an_event_when_a_resource_is_resolved($container, EventDispatcherInterface $dispatcher)
    {
        $this->beConstructedWith($container, $dispatcher);

        $dispatcher->dispatch(Events::BEFORE_RESOURCE_RESOLVED, Argument::type('Knp\Rad\ResourceResolver\Event\ResourceEvent\BeforeResourceResolvedEvent'))->shouldBeCalled();
        $dispatcher->dispatch(Events::RESOURCE_RESOLVED, Argument::type('Knp\Rad\ResourceResolver\Event\ResourceEvent\ResourceResolvedEvent'))->shouldBeCalled();

        $this->resolveResource('@app.my.route', 'setOption', ['"myFirstParameter"', true]);
    }

    function it_doesnt_resolve_resource_if_listener_does($container, EventDispatcherInterface $dispatcher, $route)
    {
        $result = 'toto';
        $this->beConstructedWith($container, $dispatcher);

        $dispatcher
            ->dispatch(Events::BEFORE_RESOURCE_RESOLVED, Argument::type('Knp\Rad\ResourceResolver\Event\ResourceEvent\BeforeResourceResolvedEvent'))
            ->shouldBeCalled()
            ->will(function ($args) use ($result) {
                list($name, $event) = $args;
                $event->setResource($result);
            })
        ;
        $dispatcher->dispatch(Events::RESOURCE_RESOLVED, Argument::type('Knp\Rad\ResourceResolver\Event\ResourceEvent\ResourceResolvedEvent'))->shouldBeCalled();
        $route->setOption(Argument::cetera())->shouldNotBeCalled();

        $this->resolveResource('@app.my.route', 'setOption', ['"myFirstParameter"', true])->shouldReturn($result);
    }
}
