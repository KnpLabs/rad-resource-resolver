<?php

namespace Knp\Rad\ResourceResolver\ParameterResolver;

use Knp\Rad\ResourceResolver\ParameterResolver;

class IntegerResolver implements ParameterResolver
{
    public function resolve($string, array $parameters)
    {
        if (!preg_match('/.*["\']+.*/', $string)) {
            if (preg_match('/^[0-9]+$/', $string)) {
                return (int) $string;
            }
        }

        return;
    }
}
