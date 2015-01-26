<?php

namespace Knp\Rad\ResourceResolver\ParameterResolver;

use Knp\Rad\ResourceResolver\ParameterResolver;

class FloatResolver implements ParameterResolver
{
    public function resolve($string, array $parameters)
    {
        if (!preg_match('/.*["\']+.*/', $string)) {
            if (preg_match('/^\d+\.\d+$/', $string)) {
                return floatval($string);
            }
        }
    }
}
