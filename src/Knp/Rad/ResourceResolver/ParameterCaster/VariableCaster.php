<?php

namespace Knp\Rad\ResourceResolver\ParameterCaster;

use Knp\Rad\ResourceResolver\ParameterCaster;
use Symfony\Component\HttpFoundation\RequestStack;

class VariableCaster implements ParameterCaster
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
    public function supports($value)
    {
        if (false === is_string($value)) {
            return false;
        }

        return 1 === preg_match('/^\$[a-zA-Z_]+/', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function cast($value)
    {
        $routeParameters = $this->getRequest()->attributes->get('_route_params');

        foreach ($routeParameters as $routeParameter => $routevalue) {
            if (substr($value, 1) === $routeParameter) {
                return $routevalue;
            }
        }

        return $value;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request|null
     */
    private function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }
}
