<?php

namespace spec\Knp\Rad\ResourceResolver\Parser;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SyntaxParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ResourceResolver\Parser\SyntaxParser');
    }

    function it_parses_a_resource_syntax()
    {
        $this->parse('@myService::myMethod("firstParameter", 12)')->shouldReturn([
            'serviceId'    => 'myService',
            'method'     => 'myMethod',
            'parameters' => ["firstParameter", 12]
        ]);
    }
}
