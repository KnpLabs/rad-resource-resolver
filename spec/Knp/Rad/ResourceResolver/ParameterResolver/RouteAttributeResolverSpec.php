<?php

namespace spec\Knp\Rad\ResourceResolver\ParameterResolver;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RouteAttributeResolverSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ResourceResolver\ParameterResolver\RouteAttributeResolver');
    }

    function it_matches_string_with_route_parameters()
    {
        $this->resolve('$id', [
            '_route_params' => [
                'id'   => 123,
                'test' => 'mytestvalue',
                'foo'  => 123.098,
                'bar'  => true
            ]
        ])->shouldReturn(123);

        $this->resolve('$test', [
            '_route_params' => [
                'id'   => 123,
                'test' => 'mytestvalue',
                'foo'  => 123.098,
                'bar'  => true
            ]
        ])->shouldReturn('mytestvalue');

        $this->resolve('$foo', [
            '_route_params' => [
                'id'   => 123,
                'test' => 'mytestvalue',
                'foo'  => 123.098,
                'bar'  => true
            ]
        ])->shouldReturn(123.098);

        $this->resolve('$bar', [
            '_route_params' => [
                'id'   => 123,
                'test' => 'mytestvalue',
                'foo'  => 123.098,
                'bar'  => true
            ]
        ])->shouldReturn(true);

        $this->resolve('$noExistantValue', [
            '_route_params' => [
                'id'   => 123,
                'test' => 'mytestvalue',
                'foo'  => 123.098,
                'bar'  => true
            ]
        ])->shouldReturn(null);

        $this->resolve('id', [
            '_route_params' => [
                'id'   => 123,
                'test' => 'mytestvalue',
                'foo'  => 123.098,
                'bar'  => true
            ]
        ])->shouldReturn(null);
    }
}
