<?php

namespace Knp\Rad\ResourceResolver\Caster;

use Knp\Rad\ResourceResolver\Caster;
use Symfony\Component\HttpFoundation\RequestStack;

final class VariableCaster implements Caster
{
    /**
     * @var RequestStack $requestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function cast($value)
    {
        $searched   = substr($value, 1);
        $parameters = $this
            ->requestStack
            ->getCurrentRequest()
            ->attributes
            ->get('_route_params')
        ;

        foreach ($parameters as $parameter => $value) {
            if ($searched === $parameter) {
                return $value;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($value)
    {
        return is_string($value) && 1 === preg_match('/^\$[a-zA-Z_]+/', $value);
    }
}
