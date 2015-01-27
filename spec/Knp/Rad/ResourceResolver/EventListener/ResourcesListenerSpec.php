<?php

namespace spec\Knp\Rad\ResourceResolver\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Knp\Rad\ResourceResolver\Parser;
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
        $parameterBag->all()->willReturn(['id' => 210]);

        $parser->parse($firstPath, ['id' => 210])->willReturn([
            'serviceId'  => '@app_user_repository',
            'method'     => 'myMethod',
            'parameters' => ["myFirstParameter", true]
        ]);
        $parser->parse($secondPath, ['id' => 210])->willReturn([
            'serviceId'  => '@app_article_repository',
            'method'     => 'myMethod',
            'parameters' => ['foo', 12]
        ]);

        $resolver
            ->resolveResource('@app_user_repository', 'myMethod', ["myFirstParameter", true])
            ->willReturn($genericEvent1)
        ;

        $resolver
            ->resolveResource('@app_article_repository', 'myMethod', ['foo', 12])
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
