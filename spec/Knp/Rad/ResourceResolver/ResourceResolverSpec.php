<?php

namespace spec\Knp\Rad\ResourceResolver;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerInterface;
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

    function it_resolves_a_resource(Route $route, Reference $reference, $container)
    {
        $container->get('@app.my.route')->willReturn($route);
        $route->setOption('"myFirstParameter"', true)->willReturn($reference);

        $this
            ->resolveResource('@app.my.route', 'setOption', ['"myFirstParameter"', true])
            ->shouldReturn($reference)
        ;
    }
}
