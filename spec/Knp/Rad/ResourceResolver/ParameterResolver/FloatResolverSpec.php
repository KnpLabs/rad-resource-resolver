<?php

namespace spec\Knp\Rad\ResourceResolver\ParameterResolver;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FloatResolverSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ResourceResolver\ParameterResolver\FloatResolver');
    }

    function it_converts_float_string_in_floats()
    {
        $this->resolve('123.23', [])->shouldReturn((float) 123.23);
        $this->resolve('1098123.098023', [])->shouldReturn((float) 1098123.098023);
        $this->resolve('109.8123.098023', [])->shouldReturn(null);
        $this->resolve('12323', [])->shouldReturn(null);
        $this->resolve('nauriste', [])->shouldReturn(null);
        $this->resolve('123.098nrsu', [])->shouldReturn(null);
        $this->resolve('false', [])->shouldReturn(null);
    }
}
