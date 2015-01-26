<?php

namespace spec\Knp\Rad\ResourceResolver\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Knp\Rad\ResourceResolver\Parser\Parser;
use Symfony\Component\EventDispatcher\GenericEvent;
use Knp\Rad\ResourceResolver\ResourceResolver;

class ResourcesListenerSpec extends ObjectBehavior
{
    function let(Parser $parser, ResourceResolver $resolver)
    {
        $this->beConstructedWith($parser, $resolver);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ResourceResolver\EventListener\ResourcesListener');
    }

    function it_resolves_resources(
        FilterControllerEvent $event,
        Request $request,
        ParameterBag $parameterBag,
        GenericEvent $genericEvent1,
        GenericEvent $genericEvent2,
        $parser,
        $resolver
    ) {
        $request->attributes = $parameterBag;
        $event->getRequest()->willReturn($request);
        $firstPath  = '@app_user_repository::myMethod("myFirstParameter", true)';
        $secondPath = '@app_article_repository::myMethod("foo", 12)';

        $resources = [
            'user'    => $firstPath,
            'article' => $secondPath
        ];

        $parameterBag->get('_resources')->willReturn($resources);

        $parser->parse($firstPath)->willReturn([
            'serviceId'  => '@myService',
            'method'     => 'myMethod',
            'parameters' => [1, 'Test']
        ]);
        $parser->parse($secondPath)->willReturn([
            'serviceId'  => '@myService',
            'method'     => 'myMethod',
            'parameters' => [2, 'Test2']
        ]);

        $resolver
            ->resolveResource('@myService', 'myMethod', [1, 'Test'])
            ->willReturn($genericEvent1)
        ;

        $resolver
            ->resolveResource('@myService', 'myMethod', [2, 'Test2'])
            ->willReturn($genericEvent2)
        ;

        $parameterBag->set('user', $genericEvent1)->shouldBeCalled();
        $parameterBag->set('article', $genericEvent2)->shouldBeCalled();

        $resources = [
            'user'    => $genericEvent1,
            'article' => $genericEvent2
        ];

        $this->resolveResources($event);
    }
}
