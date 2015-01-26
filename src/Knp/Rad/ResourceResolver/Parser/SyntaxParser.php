<?php

namespace Knp\Rad\ResourceResolver\Parser;

use Knp\Rad\ResourceResolver\Parser;
use Knp\Rad\ResourceResolver\ParameterResolver;

class SyntaxParser implements Parser
{
    private $parameterResolvers;

    public function __construct()
    {
        $this->parameterResolvers = [];
    }

    public function parse($string, array $routeParameters)
    {
        $pattern = '/^@(?<serviceId>[a-zA-Z0-9_\-\.]+)::(?<method>[a-zA-Z0-9_-]+)\((?<parameters>.*)\)$/';
        $parsed = preg_match($pattern, $string, $matches);

        if (!$parsed) {
            throw new \Exception('The string could not be parsed');
        }

        $parameters = explode(',', $matches['parameters']);

        $parameters = array_map(function($value) use ($routeParameters) {

            $value = trim($value);
            $convertedValue = null;

            foreach ($this->parameterResolvers as $parameterResolver) {
                $convertedValue = $parameterResolver->resolve($value, $routeParameters);
                if ($convertedValue) {
                    $value = $convertedValue;
                    break;
                }
            }

            if (null === $convertedValue) {
                $value = $this->cleanValueToString($value);
            }

            return $value;
        }, $parameters);

        return [
            'serviceId'  => $matches['serviceId'],
            'method'     => $matches['method'],
            'parameters' => $parameters
        ];
    }

    public function addParameterResolver(ParameterResolver $parameterResolver)
    {
        $this->parameterResolvers[] = $parameterResolver;

        return $this;
    }

    protected function cleanValueToString($value)
    {
        $value = trim($value, '"');
        $value = trim($value, '\'');

        return $value;
    }
}
