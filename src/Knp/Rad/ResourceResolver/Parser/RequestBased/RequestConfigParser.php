<?php

namespace Knp\Rad\ResourceResolver\Parser\RequestBased;

use Knp\Rad\ResourceResolver\Parser;
use Knp\Rad\ResourceResolver\Resource\CallableResource;
use Symfony\Component\HttpFoundation\RequestStack;

final class RequestConfigParser implements Parser
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

        $required = true;

        if (isset($config['required'])) {
            $required = boolval($config['required']);
        } elseif (in_array($this->getLastConfigItem($config), ['true','false'])) {
            $required = $config[count($config)-1];
        }

        return new CallableResource($name, [$request->{$bagName}, 'get'], [$key], $required);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($config)
    {
        if (is_string($config)) {
            $config = [$config];
        }

        return is_array($config) && count($config) >= 1 && 2 === count($this->extractBagNameAndKey($config));
    }

    /**
     * @param $config
     *
     * @return array
     */
    private function extractBagNameAndKey($config)
    {
        $matches = [];

        $expr = '';

        if (isset($config['expr'])) {
            $expr = $config['expr'];
        } elseif (isset($config[0])) {
            $expr = $config[0];
        } else {
            throw new \InvalidArgumentException('You need to provide an expression.');
        }

        preg_match('#^\$(request|query|headers|attributes)\-\>(.+)#', $expr, $matches);

        if (count($matches) === 3) {
            array_shift($matches);
        }

        return array_filter($matches);
    }

    private function getLastConfigItem(array $config)
    {
        return array_pop($config);
    }
}
