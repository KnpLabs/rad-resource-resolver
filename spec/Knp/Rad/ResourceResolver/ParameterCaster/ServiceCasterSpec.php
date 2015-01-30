<?php

namespace spec\Knp\Rad\ResourceResolver\ParameterCaster;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\DataCollector\RequestDataCollector;
use Symfony\Component\HttpKernel\EventListener\DumpListener;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceCasterSpec extends ObjectBehavior
{
    function let(ContainerInterface $container)
    {
        $this->beConstructedWith($container);
    }

    function it_is_initializable(ContainerInterface $container)
    {
        $this->shouldHaveType('Knp\Rad\ResourceResolver\ParameterCaster\ServiceCaster');
    }

    function it_supports_service_strings()
    {
        $this->supports('@my_service')->shouldReturn(true);
        $this->supports('@foo_bar')->shouldReturn(true);
        $this->supports('foo_bar')->shouldReturn(false);
        $this->supports('foo_@bar')->shouldReturn(false);
        $this->supports('foo_bar@')->shouldReturn(false);
        $this->supports('@')->shouldReturn(false);
    }

    function it_casts_a_service_string_in_service(
        RequestStack $requestStack,
        RequestDataCollector $requestDataCollector,
        DumpListener $dumpListener,
        $container
    ) {
        $container
            ->get('@request_stack', ContainerInterface::NULL_ON_INVALID_REFERENCE)
            ->willReturn($requestStack)
        ;

        $container
            ->get('@data_collector.request', ContainerInterface::NULL_ON_INVALID_REFERENCE)
            ->willReturn($requestDataCollector)
        ;

        $container
            ->get('@debug.dump_listener', ContainerInterface::NULL_ON_INVALID_REFERENCE)
            ->willReturn($dumpListener)
        ;

        $container
            ->get('@foo_bar', ContainerInterface::NULL_ON_INVALID_REFERENCE)
            ->willReturn('@foo_bar')
        ;

        $this->cast('@request_stack')->shouldReturn($requestStack);
        $this->cast('@data_collector.request')->shouldReturn($requestDataCollector);
        $this->cast('@debug.dump_listener')->shouldReturn($dumpListener);
        $this->cast('@foo_bar')->shouldReturn('@foo_bar');
    }
}
