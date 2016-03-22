<?php

namespace spec\Knp\Rad\ResourceResolver\ResourceContainer;

use PhpSpec\ObjectBehavior;

class ResourceContainerSpec extends ObjectBehavior
{
    function let($resource1, $resource2, $resource3)
    {
        $this->beConstructedWith([
            'resource1' => $resource1,
            'resource3' => $resource3,
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ResourceResolver\ResourceContainer\ResourceContainer');
    }

    function it_returns_resources($resource1, $resource3)
    {
        $this->getResources()->shouldReturn([
            'resource1' => $resource1,
            'resource3' => $resource3,
        ]);
    }

    function it_receives_new_resources($resource1, $resource2, $resource3)
    {
        $this->addResource('resource2', $resource2);

        $this->getResources()->shouldReturn([
            'resource1' => $resource1,
            'resource3' => $resource3,
            'resource2' => $resource2,
        ]);
    }

    function it_can_replace_a_resource($resource1, $resource2)
    {
        $this->addResource('resource3', $resource2);

        $this->getResources()->shouldReturn([
            'resource1' => $resource1,
            'resource3' => $resource2,
        ]);
    }

    function it_removes_a_resource($resource1)
    {
        $this->removeResource('resource3');

        $this->getResources()->shouldReturn([
            'resource1' => $resource1,
        ]);
    }

    function it_says_if_resource_exists()
    {
        $this->hasResource('resource1')->shouldReturn(true);
        $this->hasResource('resource3')->shouldReturn(true);
        $this->hasResource('resource2')->shouldReturn(false);
    }

    function it_fails_when_an_unexisting_resource_is_asked()
    {
        $this
            ->shouldThrow(new \DomainException('Resource "no" not found, "resource1", "resource3" available.'))
            ->duringGetResource('no')
        ;

        $this
            ->shouldThrow(new \DomainException('Resource "no" not found, "resource1", "resource3" available.'))
            ->duringRemoveResource('no')
        ;
    }
}
