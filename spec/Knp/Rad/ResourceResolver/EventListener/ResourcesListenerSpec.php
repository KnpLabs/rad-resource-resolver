<?php

namespace spec\Knp\Rad\ResourceResolver\EventListener;

use Knp\Rad\ResourceResolver\ParameterCaster;
use Knp\Rad\ResourceResolver\Parser;
use Knp\Rad\ResourceResolver\ResourceContainer;
use Knp\Rad\ResourceResolver\ResourceResolver;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ResourcesListenerSpec extends ObjectBehavior
{
    function let(
        ResourceResolver $resolver,
        Parser $customSyntaxParser,
        ParameterCaster $variableCaster,
        ResourceContainer $container
    ) {
        $this->beConstructedWith($resolver, $container);
        $this->addParser($customSyntaxParser);
        $this->addParameterCaster($variableCaster);
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
        $resolver,
        $customSyntaxParser,
        $variableCaster,
        $container
    ) {
        $event->getRequest()->willReturn($request);
        $request->attributes = $parameterBag;
        $customPath  = '@app_user_repository::myMethod("myFirstParameter", true)';
        $yamlArray = [
            'service'   => 'app_article_repository',
            'method'    => 'myMethod',
            'arguments' => ['foo', '$id']
        ];

        $resources = [
            'user'    => $customPath,
            'article' => $yamlArray
        ];

        $parameterBag->get('_resources', [])->willReturn($resources);

        $customSyntaxParser->supports($customPath)->willReturn(true);
        $customSyntaxParser->parse($customPath)->willReturn([
            'service'   => 'app_user_repository',
            'method'    => 'myMethod',
            'arguments' => ['myFirstParameter']
        ]);


        $resolver
            ->resolveResource('app_user_repository', 'myMethod', ['myFirstParameter'])
            ->willReturn($genericEvent1)
        ;

        $parameterBag->set('user', $genericEvent1)->shouldBeCalled();

        $customSyntaxParser->supports($yamlArray)->willReturn(false);

        $variableCaster->supports('myFirstParameter')->willReturn(false);
        $variableCaster->supports('foo')->willReturn(false);
        $variableCaster->supports('$id')->willReturn(true);
        $variableCaster->cast('$id')->willReturn(240);

        $resolver
            ->resolveResource('app_article_repository', 'myMethod', ['foo', 240])
            ->willReturn($genericEvent2)
        ;

        $parameterBag->set('article', $genericEvent2)->shouldBeCalled();

        $container->addResource('article', $genericEvent2)->shouldBeCalled();
        $container->addResource('user', $genericEvent1)->shouldBeCalled();

        $this->resolveResources($event);
    }

    function it_does_nothing_if_there_is_no_resource_to_resolve(
        FilterControllerEvent $event,
        Request $request,
        ParameterBag $parameterBag,
        $resolver
    ) {
        $event->getRequest()->willReturn($request);
        $request->attributes = $parameterBag;
        $parameterBag->get('_resources', [])->willReturn([]);

        $resolver->resolveResource(Argument::cetera())->shouldNotBeCalled();

        $this->resolveResources($event);
    }
}
