<?php

namespace spec\Knp\Rad\ResourceResolver;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RoutingNormalizerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Knp\Rad\ResourceResolver\RoutingNormalizer');
    }

    function it_adds_default_value_to_array()
    {
        $this->normalizeDeclaration([
            'service'   => 'app_article_repository',
            'method'    => 'myMethod',
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
}
