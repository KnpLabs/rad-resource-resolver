<?php

namespace Knp\Rad\ResourceResolver\ParameterResolver;

use Knp\Rad\ResourceResolver\ParameterResolver;

class BooleanResolver implements ParameterResolver
{
    public function resolve($string, array $parameters)
    {
        switch ($string) {
            case 'true':
            case 'TRUE':
                return true;
            case 'false':
            case 'FALSE':
                return false;
            default:
                return;
        }
    }
}
