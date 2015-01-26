<?php

namespace spec\Knp\Rad\ResourceResolver\ParameterResolver;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IntegerResolverSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ResourceResolver\ParameterResolver\IntegerResolver');
    }

    function it_converts_integer_strings_as_integers()
    {
        $this->resolve('12nrs32', [])->shouldReturn(null);
        $this->resolve('123', [])->shouldReturn((int) 123);
        $this->resolve('123.32', [])->shouldReturn(null);
        $this->resolve('12²', [])->shouldReturn(null);
        $this->resolve('true', [])->shouldReturn(null);
    }
}
