<?php

namespace spec\Knp\Rad\ResourceResolver\ParameterCaster;

use Knp\Rad\ResourceResolver\ParameterCaster;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArrayCasterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ResourceResolver\ParameterCaster\ArrayCaster');
    }

    function it_resolves_array(ParameterCaster $c1, ParameterCaster $c2, $service)
    {
        $c1->supports(Argument::any())->willReturn(false);
        $c1->supports('$var')->willReturn(true);
        $c1->cast('$var')->willReturn('result');

        $c2->supports(Argument::any())->willReturn(false);
        $c2->supports('@service')->willReturn(true);
        $c2->cast('@service')->willReturn($service);

        $this->addParameterCaster($c1);
        $this->addParameterCaster($c2);

        $array = ['$var', '@service', 'toto'];

        $this->cast($array)->shouldReturn(['result', $service, 'toto']);
    }
}
