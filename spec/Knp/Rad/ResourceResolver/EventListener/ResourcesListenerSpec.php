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
use Symfony\Component\HttpFoundation\Response;
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
        GenericEvent $genericEvent3,
        $resolver,
        $customSyntaxParser,
        $variableCaster,
        $container
    ) {
        $event->getRequest()->willReturn($request);
        $request->attributes = $parameterBag;
        $customPath          = '@app_user_repository::myMethod("myFirstParameter", true)';
        $yamlArray           = [
            'service'   => 'app_article_repository',
            'method'    => 'myMethod',
            'arguments' => ['foo', '$id'],
        ];
        $concise    = ['app_category_repository:myMethod', ['$id'], true];
        $normalized = [
            'service'   => 'app_category_repository',
            'method'    => 'myMethod',
            'arguments' => ['$id'],
            'required'  => false,
        ];

        $resources = [
            'user'     => $customPath,
            'article'  => $yamlArray,
            'category' => $concise,
        ];

        $parameterBag->get('_resources', [])->willReturn($resources);

        $customSyntaxParser->supports($customPath)->willReturn(true);
        $customSyntaxParser->parse($customPath)->willReturn([
            'service'   => 'app_user_repository',
            'method'    => 'myMethod',
            'arguments' => ['myFirstParameter'],
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

        $customSyntaxParser->supports($concise)->willReturn(false);

        $variableCaster->supports('$id')->willReturn(true);
        $variableCaster->cast('$id')->willReturn(240);

        $resolver
            ->resolveResource('app_category_repository', 'myMethod', [240])
            ->willReturn($genericEvent3)
        ;

        $parameterBag->set('category', $genericEvent3)->shouldBeCalled();

        $container->addResource('user', $genericEvent1)->shouldBeCalled();
        $container->addResource('article', $genericEvent2)->shouldBeCalled();
        $container->addResource('category', $genericEvent3)->shouldBeCalled();

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

    function it_throws_a_not_found_exception_if_the_resource_could_not_be_found(
        FilterControllerEvent $event,
        Request $request,
        ParameterBag $parameterBag,
        $resolver,
        $customSyntaxParser,
        $variableCaster,
        $container
    ) {
        $event->getRequest()->willReturn($request);
        $request->attributes = $parameterBag;
        $customPath          = '@app_user_repository::myMethod("myFirstParameter")';

        $resources = [
            'user' => $customPath,
        ];

        $parameterBag->get('_resources', [])->willReturn($resources);

        $customSyntaxParser->supports($customPath)->willReturn(true);
        $customSyntaxParser->parse($customPath)->willReturn([
            'service'   => 'app_user_repository',
            'method'    => 'myMethod',
            'arguments' => ['myFirstParameter'],
        ]);

        $resolver
            ->resolveResource('app_user_repository', 'myMethod', ['myFirstParameter'])
            ->willReturn(null)
        ;

        $this
            ->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('resolveResources', [$event])
        ;
    }

    function it_does_not_throw_exception_if_required_is_set_to_false(
        FilterControllerEvent $event,
        Request $request,
        ParameterBag $parameterBag,
        $resolver,
        $customSyntaxParser,
        $variableCaster,
        $container
    ) {
        $event->getRequest()->willReturn($request);
        $request->attributes = $parameterBag;
        $customPath          = '@app_user_repository::myMethod("myFirstParameter")';

        $resources = [
            'user' => $customPath,
        ];

        $parameterBag->get('_resources', [])->willReturn($resources);

        $customSyntaxParser->supports($customPath)->willReturn(true);
        $customSyntaxParser->parse($customPath)->willReturn([
            'service'   => 'app_user_repository',
            'method'    => 'myMethod',
            'arguments' => ['myFirstParameter'],
            'required'  => false,
        ]);

        $resolver
            ->resolveResource('app_user_repository', 'myMethod', ['myFirstParameter'])
            ->willReturn(null)
        ;

        $parameterBag->set('user', null)->shouldBeCalled();
        $container->addResource('user', null)->shouldBeCalled();

        $this->resolveResources($event);
    }

    function it_throws_a_forbidden_exception_if_the_resource_could_not_be_found(
        FilterControllerEvent $event,
        Request $request,
        ParameterBag $parameterBag,
        $resolver,
        $customSyntaxParser,
        $variableCaster,
        $container
    ) {
        $event->getRequest()->willReturn($request);
        $request->attributes = $parameterBag;
        $customPath          = '@app_user_repository::myMethod("myFirstParameter")';

        $resources = [
            'user' => $customPath,
        ];

        $parameterBag->get('_resources', [])->willReturn($resources);

        $customSyntaxParser->supports($customPath)->willReturn(true);
        $customSyntaxParser->parse($customPath)->willReturn([
            'service'    => 'app_user_repository',
            'method'     => 'myMethod',
            'on-missing' => Response::HTTP_FORBIDDEN,
            'arguments'  => ['myFirstParameter'],
        ]);

        $resolver
            ->resolveResource('app_user_repository', 'myMethod', ['myFirstParameter'])
            ->willReturn(null)
        ;

        $this
            ->shouldThrow('Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException')
            ->during('resolveResources', [$event])
        ;
    }

    function it_throws_an_unauthorized_exception_if_the_resource_could_not_be_found(
        FilterControllerEvent $event,
        Request $request,
        ParameterBag $parameterBag,
        $resolver,
        $customSyntaxParser,
        $variableCaster,
        $container
    ) {
        $event->getRequest()->willReturn($request);
        $request->attributes = $parameterBag;
        $customPath          = '@app_user_repository::myMethod("myFirstParameter")';

        $resources = [
            'user' => $customPath,
        ];

        $parameterBag->get('_resources', [])->willReturn($resources);

        $customSyntaxParser->supports($customPath)->willReturn(true);
        $customSyntaxParser->parse($customPath)->willReturn([
            'service'    => 'app_user_repository',
            'method'     => 'myMethod',
            'on-missing' => Response::HTTP_UNAUTHORIZED,
            'arguments'  => ['myFirstParameter'],
        ]);

        $resolver
            ->resolveResource('app_user_repository', 'myMethod', ['myFirstParameter'])
            ->willReturn(null)
        ;

        $this
            ->shouldThrow('Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException')
            ->during('resolveResources', [$event])
        ;
    }
}
