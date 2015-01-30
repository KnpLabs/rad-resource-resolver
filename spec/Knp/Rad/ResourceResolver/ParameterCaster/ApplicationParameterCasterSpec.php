<?php

namespace spec\Knp\Rad\ResourceResolver\ParameterCaster;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ApplicationParameterCasterSpec extends ObjectBehavior
{
    function let(ContainerBuilder $container)
    {
        $this->beConstructedWith($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ResourceResolver\ParameterCaster\ApplicationParameterCaster');
    }

    function it_supports_application_parameter_strings()
    {
        $this->supports('%my_parameter%')->shouldReturn(true);
        $this->supports('%foobar%')->shouldReturn(true);
        $this->supports('%foo%bar%')->shouldReturn(false);
        $this->supports('%my.parameter%')->shouldReturn(true);
        $this->supports('%my_parameter%')->shouldReturn(true);
        $this->supports('true')->shouldReturn(false);
        $this->supports('@test')->shouldReturn(false);
    }

    function it_casts_application_parameter_string_in_string(
        $container
    ) {
        $container->hasParameter('%my_integer_parameter%')->willReturn(true);
        $container->hasParameter('%my_string_parameter%')->willReturn(true);
        $container->hasParameter('%my_bool_parameter%')->willReturn(true);
        $container->hasParameter('%my_array_parameter%')->willReturn(true);
        $container->hasParameter('%my_inexistant_parameter%')->willReturn(false);

        $container->getParameter('%my_integer_parameter%')->willReturn(3847);
        $container->getParameter('%my_string_parameter%')->willReturn('foo');
        $container->getParameter('%my_bool_parameter%')->willReturn(false);
        $container->getParameter('%my_array_parameter%')->willReturn(['foo' => 32, 'bar' => 'xyz']);
        $container->getParameter('%my_inexistant_parameter%')->willReturn('%my_inexistant_parameter%');

        $this->cast('%my_integer_parameter%')->shouldReturn(3847);
        $this->cast('%my_string_parameter%')->shouldReturn('foo');
        $this->cast('%my_bool_parameter%')->shouldReturn(false);
        $this->cast('%my_array_parameter%')->shouldReturn(['foo' => 32, 'bar' => 'xyz']);
        $this->cast('%my_inexistant_parameter%')->shouldReturn('%my_inexistant_parameter%');
    }
}
