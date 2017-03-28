<?php

namespace spec\Knp\Rad\ResourceResolver;

use PhpSpec\ObjectBehavior;

class RoutingNormalizerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ResourceResolver\RoutingNormalizer');
    }

    function it_normalizes_string_without_method()
    {
        $this->normalizeDeclaration('app.http.resource_provider.user_membership')->shouldReturn([
            'service'   => 'app.http.resource_provider.user_membership',
            'method'    => null,
            'arguments' => [],
            'required'  => true,
        ]);
    }

    function it_normalizes_string_with_method()
    {
        $this->normalizeDeclaration('app.repository.article_repository:getNewestArticle')->shouldReturn([
            'service'   => 'app.repository.article_repository',
            'method'    => 'getNewestArticle',
            'arguments' => [],
            'required'  => true,
        ]);
    }

    function it_adds_default_value_to_array()
    {
        $this->normalizeDeclaration([
            'service' => 'app_article_repository',
            'method'  => 'myMethod',
        ])->shouldBeLike([
            'service'   => 'app_article_repository',
            'method'    => 'myMethod',
            'arguments' => [],
            'required'  => true,
        ]);
    }

    function it_does_nothing_for_an_array_already_normalized()
    {
        $this->normalizeDeclaration([
            'service'   => 'app_product_repository',
            'method'    => 'findAllSoldBy',
            'arguments' => ['$productId'],
            'required'  => false,
        ])->shouldBeLike([
            'service'   => 'app_product_repository',
            'method'    => 'findAllSoldBy',
            'arguments' => ['$productId'],
            'required'  => false,
        ]);
    }

    function it_normalizes_array_without_arguments()
    {
        $this->normalizeDeclaration(['app.repository.product:findNewest'])->shouldBeLike([
            'service'   => 'app.repository.product',
            'method'    => 'findNewest',
            'arguments' => [],
            'required'  => true,
        ]);
    }
}
