<?php

namespace Knp\Rad\ResourceResolver\ParameterResolver;

use Knp\Rad\ResourceResolver\ParameterResolver;

class RouteAttributeResolver implements ParameterResolver
{
    public function resolve($string, array $parameters)
    {
        if (!substr($string, 0, 1) === '$') {
            return;
        }

        foreach ($parameters['_route_params'] as $routeParameter => $value) {
            if (substr($string, 1) === $routeParameter) {
                return $value;
            }
        }

        return;
    }
}
