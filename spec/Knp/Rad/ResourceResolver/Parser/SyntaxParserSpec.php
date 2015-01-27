<?php

namespace spec\Knp\Rad\ResourceResolver\Parser;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Knp\Rad\ResourceResolver\ParameterResolver\BooleanResolver;
use Knp\Rad\ResourceResolver\ParameterResolver\FloatResolver;
use Knp\Rad\ResourceResolver\ParameterResolver\IntegerResolver;
use Knp\Rad\ResourceResolver\ParameterResolver\RouteAttributeResolver;

class SyntaxParserSpec extends ObjectBehavior
{
    function let(
        BooleanResolver $booleanResolver,
        FloatResolver $floatResolver,
        IntegerResolver $integerResolver,
        RouteAttributeResolver $routeAttributeResolver
    ) {
        $this->addParameterResolver($booleanResolver);
        $this->addParameterResolver($floatResolver);
        $this->addParameterResolver($integerResolver);
        $this->addParameterResolver($routeAttributeResolver);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ResourceResolver\Parser\SyntaxParser');
    }

    function it_parses_a_resource_syntax(
        $booleanResolver,
        $floatResolver,
        $integerResolver,
        $routeAttributeResolver
    ) {
        $booleanResolver->resolve('"firstParameter"', [])->willReturn(null);
        $floatResolver->resolve('"firstParameter"', [])->willReturn(null);
        $integerResolver->resolve('"firstParameter"', [])->willReturn(null);
        $routeAttributeResolver->resolve('"firstParameter"', [])->willReturn(null);

        $this->parse('@myService::myMethod("firstParameter")', [])->shouldReturn([
            'serviceId'    => 'myService',
            'method'     => 'myMethod',
            'parameters' => ["firstParameter"]
        ]);

        $booleanResolver->resolve('foo', [])->willReturn(null);
        $floatResolver->resolve('foo', [])->willReturn(null);
        $integerResolver->resolve('foo', [])->willReturn(null);
        $routeAttributeResolver->resolve('foo', [])->willReturn(null);

        $booleanResolver->resolve('123', [])->willReturn(null);
        $floatResolver->resolve('123', [])->willReturn(null);
        $integerResolver->resolve('123', [])->willReturn((int) 123);
        $routeAttributeResolver->resolve('123', [])->willReturn(null);

        $booleanResolver->resolve('340.09', [])->willReturn(null);
        $floatResolver->resolve('340.09', [])->willReturn((float) 340.09);
        $integerResolver->resolve('340.09', [])->willReturn(null);
        $routeAttributeResolver->resolve('340.09', [])->willReturn(null);

        $this->parse('@myService::myMethod(foo, 123, 340.09)', [])->shouldReturn([
            'serviceId'    => 'myService',
            'method'     => 'myMethod',
            'parameters' => ["foo", 123, 340.09]
        ]);

        $booleanResolver->resolve('$id', ['id' => 213])->willReturn(null);
        $floatResolver->resolve('$id', ['id' => 213])->willReturn(null);
        $integerResolver->resolve('$id', ['id' => 213])->willReturn(null);
        $routeAttributeResolver->resolve('$id', ['id' => 213])->willReturn(213);

        $booleanResolver->resolve('true', ['id' => 213])->willReturn(true);
        $floatResolver->resolve('true', ['id' => 213])->willReturn(null);
        $integerResolver->resolve('true', ['id' => 213])->willReturn(null);
        $routeAttributeResolver->resolve('true', ['id' => 213])->willReturn(null);

        $this->parse('@myService::myMethod($id, true)', ['id' => 213])->shouldReturn([
            'serviceId'    => 'myService',
            'method'     => 'myMethod',
            'parameters' => [213, true]
        ]);
    }
}
