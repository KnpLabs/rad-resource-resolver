<?php

namespace Knp\Rad\ResourceResolver;

class RoutingNormalizer
{
    /**
     * Normalizes string and array declarations into associative array.
     *
     * @param string|array $declaration
     *
     * @return array
     */
    public function normalizeDeclaration($declaration)
    {
        if (is_string($declaration)) {
            return $this->normalizeString($declaration);
        }

        // Normalize numerically indexed array
        if (array_keys($declaration) === array_keys(array_values($declaration))) {
            return $this->normalizeArray($declaration);
        }

        if (isset($declaration['arguments']) && !is_array($declaration['arguments'])) {
            throw new \InvalidArgumentException('The "arguments" parameter should be an array of arguments.');
        }

        // Adds default value to associative array
        return array_merge(['required' => true, 'arguments' => []], $declaration);
    }

    private function normalizeString($declaration)
    {
        $service = $declaration;
        $method  = null;

        if (strpos($declaration, ':') !== false) {
            list($service, $method) = explode(':', $declaration);
        }

        return [
            'service'   => $service,
            'method'    => $method,
            'arguments' => [],
            'required'  => true,
        ];
    }

    private function normalizeArray($declaration)
    {
        if (isset($declaration[1]) && !is_array($declaration[1])) {
            throw new \InvalidArgumentException('The second argument for a resource configuration, when expressed with a numerically indexed array, should be an array of arguments.');
        }

        if (false === strpos($declaration[0], ':')) {
            throw new \RuntimeException('The first argument for a resource configuration, when expressed with a numerically indexed array, should be a string containing the service and the method used, seperated by a colon.');
        }

        list($service, $method) = explode(':', $declaration[0]);

        return [
            'service'   => $service,
            'method'    => $method,
            'arguments' => isset($declaration[1]) ? $declaration[1] : [],
            'required'  => isset($declaration[2]) ? (bool) $declaration[2] : true,
        ];
    }
}
