<?php

namespace Knp\Rad\ResourceResolver\ParameterCaster;

use Knp\Rad\ResourceResolver\ParameterCaster;
use Symfony\Component\HttpFoundation\RequestStack;

class VariableCaster implements ParameterCaster
{
    private $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function supports($string)
    {
        return 1 === preg_match('/^\$[a-zA-Z_]+/', $string);
    }

    public function cast($string)
    {
        $routeParameters = $this->request->attributes->get('_route_params');

        foreach ($routeParameters as $routeParameter => $value) {
            if (substr($string, 1) === $routeParameter) {
                return $value;
            }
        }

        return $string;
    }
}
