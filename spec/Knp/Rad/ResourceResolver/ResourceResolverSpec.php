<?php

namespace spec\Knp\Rad\ResourceResolver;

use Knp\Rad\ResourceResolver\Events;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Route;

class ResourceResolverSpec extends ObjectBehavior
{
    function let(ContainerInterface $container)
    {
        $this->beConstructedWith($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ResourceResolver\ResourceResolver');
    }

    function it_resolves_a_resource($container, Route $route, $reference)
    {
        $container->get('app.my.route')->willReturn($route);
        $route->setOption('"myFirstParameter"', true)->willReturn($reference);

        $this
            ->resolveResource('app.my.route', 'setOption', ['"myFirstParameter"', true])
            ->shouldReturn($reference)
        ;
    }

    function it_dispatches_an_event_when_a_resource_is_resolved($container, EventDispatcherInterface $dispatcher, Route $route)
    {
        $this->beConstructedWith($container, $dispatcher);

        $container->get('app.my.route')->willReturn($route);
        $dispatcher
            ->dispatch(Events::BEFORE_RESOURCE_RESOLVED, Argument::type('Knp\Rad\ResourceResolver\Event\ResourceResolvedEvent\BeforeResourceResolvedEvent'))
            ->shouldBeCalled()
        ;
        $dispatcher
            ->dispatch(Events::RESOURCE_RESOLVED, Argument::type('Knp\Rad\ResourceResolver\Event\ResourceResolvedEvent\ResourceResolvedEvent'))
            ->shouldBeCalled()
        ;

        $this->resolveResource('app.my.route', 'setOption', ['"myFirstParameter"', true]);
    }

    function it_doesnt_resolve_resource_if_listener_does($container, EventDispatcherInterface $dispatcher, Route $route)
    {
        $result = 'toto';
        $this->beConstructedWith($container, $dispatcher);

        $dispatcher
            ->dispatch(Events::BEFORE_RESOURCE_RESOLVED, Argument::type('Knp\Rad\ResourceResolver\Event\ResourceResolvedEvent\BeforeResourceResolvedEvent'))
            ->shouldBeCalled()
            ->will(function ($args) use ($result) {
                list($name, $event) = $args;
                $event->setResource($result);
            })
        ;
        $dispatcher->dispatch(Events::RESOURCE_RESOLVED, Argument::type('Knp\Rad\ResourceResolver\Event\ResourceResolvedEvent\ResourceResolvedEvent'))->shouldBeCalled();
        $route->setOption(Argument::cetera())->shouldNotBeCalled();

        $this->resolveResource('@app.my.route', 'setOption', ['"myFirstParameter"', true])->shouldReturn($result);
    }
}
