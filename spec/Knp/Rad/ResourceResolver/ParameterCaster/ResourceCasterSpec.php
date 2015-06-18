<?php

namespace spec\Knp\Rad\ResourceResolver\ParameterCaster;

use Knp\Rad\ResourceResolver\ResourceContainer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResourceCasterSpec extends ObjectBehavior
{
    function let(ResourceContainer $container)
    {
        $this->beConstructedWith($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ResourceResolver\ParameterCaster\ResourceCaster');
    }

    function it_supports_resource_strings()
    {
        $this->supports('&my_resource')->shouldReturn(true);
        $this->supports('&foo_bar')->shouldReturn(true);
        $this->supports('foo_bar')->shouldReturn(false);
        $this->supports('foo_&bar')->shouldReturn(false);
        $this->supports('foo_bar&')->shouldReturn(false);
        $this->supports('&')->shouldReturn(false);
    }

    function it_casts_a_resource_string_in_resource($resource, $resource2, $container) {
        $container
            ->getResource('my_resource')
            ->willReturn($resource)
        ;

        $container
            ->getResource('my_resource2')
            ->willReturn($resource2)
        ;

        $this->cast('&my_resource')->shouldReturn($resource);
        $this->cast('&my_resource2')->shouldReturn($resource2);
    }
}
