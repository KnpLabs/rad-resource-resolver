<?php

namespace spec\Knp\Rad\ResourceResolver\ParameterCaster;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class VariableCasterSpec extends ObjectBehavior
{
    function let(RequestStack $requestStack, Request $request)
    {
        $requestStack->getCurrentRequest()->willReturn($request);
        $this->beConstructedWith($requestStack);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ResourceResolver\ParameterCaster\VariableCaster');
    }

    function it_supports_variable_strings()
    {
        $this->supports('12309')->shouldReturn(false);
        $this->supports(123509)->shouldReturn(false);
        $this->supports(false)->shouldReturn(false);
        $this->supports('$unprst')->shouldReturn(true);
        $this->supports('$09erst')->shouldReturn(false);
        $this->supports('nrustenrute')->shouldReturn(false);  
    }

    function it_casts_variable_string_in_string($request, ParameterBag $attributes)
    {
        $request->attributes = $attributes;

        $attributes
            ->get('_route_params')
            ->willReturn([
                'foo' => 18098,
                'bar' => true,
                'xyz' => 'value'
            ])
        ;

        $this->cast('$foo')->shouldReturn(18098);
        $this->cast('$bar')->shouldReturn(true);
        $this->cast('$xyz')->shouldReturn('value');
        $this->cast('$test')->shouldReturn('$test');
    }
}
