<?php

namespace Knp\Rad\ResourceResolver\Parser\Request;

use Knp\Rad\ResourceResolver\Parser;
use Knp\Rad\ResourceResolver\Resource\CallableResource;
use Symfony\Component\HttpFoundation\RequestStack;

final class RequestParser implements Parser
{
    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * @param RequestStack $stack
     */
    public function __construct(RequestStack $stack)
    {
        $this->stack = $stack;
    }

    /**
     * {@inheritdoc}
     */
    public function parse($name, $config)
    {
        $request = $this->stack->getCurrentRequest();
        list($bagName, $key) = $this->extractBagNameAndKey($config);

        return new CallableResource($name, [$request->{$bagName}, 'get'], [$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($config)
    {
        return is_string($config) && 2 === count($this->extractBagNameAndKey($config));
    }

    /**
     * @param $config
     *
     * @return array
     */
    private function extractBagNameAndKey($config)
    {
        $matches = [];

        preg_match('#^\$(request|query|headers)\-\>(.+)#', $config, $matches);

        return array_filter($matches);
    }
}
