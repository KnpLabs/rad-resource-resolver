<?php

namespace spec\Knp\Rad\ResourceResolver\ParameterResolver;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BooleanResolverSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ResourceResolver\ParameterResolver\BooleanResolver');
    }

    function it_converts_boolean_strings_in_booleans()
    {
        $this->resolve('true', [])->shouldReturn(true);
        $this->resolve('TRUE', [])->shouldReturn(true);
        $this->resolve('TrUe', [])->shouldReturn(null);
        $this->resolve('false', [])->shouldReturn(false);
        $this->resolve('FALSE', [])->shouldReturn(false);
        $this->resolve('FaLsE', [])->shouldReturn(null);
        $this->resolve('123', [])->shouldReturn(null);
        $this->resolve('123²', [])->shouldReturn(null);
        $this->resolve('naruste', [])->shouldReturn(null);
        $this->resolve('truefalse', [])->shouldReturn(null);
        $this->resolve('5653.654', [])->shouldReturn(null);
    }
}
