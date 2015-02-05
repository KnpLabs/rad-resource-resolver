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
    public function supports($string)
    {
        return 1 === preg_match('/^\$[a-zA-Z_]+/', $string);
    }

    /**
     * {@inheritdoc}
     */
    public function cast($string)
    {
        $routeParameters = $this->getRequest()->attributes->get('_route_params');

        foreach ($routeParameters as $routeParameter => $value) {
            if (substr($string, 1) === $routeParameter) {
                return $value;
            }
        }

        return $string;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request|null
     */
    private function getRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }
}
