<?php

namespace Knp\Rad\ResourceResolver;

class RoutingNormalizer
{
    public function normalizeDeclaration($declaration)
    {
        if (array_keys($declaration) === array_keys(array_values($declaration))) {
            if (false === strpos($declaration[0], ':')) {
                throw new \RuntimeException('The first argument for a resource configuration, when expressed with a numerically indexed array, should be a string containing the service and the method used, seperated by a colon.');
            } elseif (isset($declaration[1]) && !is_array($declaration[1])) {
                throw new \RuntimeException('The second argument for a resource configuration, when expressed with a numerically indexed array, should be an array of arguments.');
            }

            list($service, $method) = explode(':', $declaration[0]);

            return [
                'service'   => $service,
                'method'    => $method,
                'arguments' => isset($declaration[1]) ? $declaration[1] : [],
                'required'  => isset($declaration[2]) ? (bool) $declaration[2] : true,
            ];
        }

        return array_merge(['required' => true, 'arguments' => []], $declaration);
    }
}
