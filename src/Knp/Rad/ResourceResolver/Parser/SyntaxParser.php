<?php

namespace Knp\Rad\ResourceResolver\Parser;

use Knp\Rad\ResourceResolver\Parser\Parser;

class SyntaxParser implements Parser
{
    public function parse($string)
    {
        $pattern = '/^@(?<serviceId>[a-zA-Z0-9_\-\.]+)::(?<method>[a-zA-Z0-9_-]+)\((?<parameters>.*)\)$/';
        $parsed = preg_match($pattern, $string, $matches);

        if (!$parsed) {
            throw new \Exception('The string could not be parsed');
        }

        $parameters = explode(',', $matches['parameters']);

        $parameters = array_map(function($value) {
            $value = trim($value);
            $stringPattern = '/.*["\'].*/';
            if (preg_match($stringPattern, $value)) {
                $value = trim($value, '"\'');
                return (string) $value;
            } elseif ($value === 'true') {
                return true;
            } elseif ($value === 'false') {
                return false;
            } elseif (preg_match('/\./', $value)) {
                return (float) $value;
            } else {
                return (int) $value;
            }
        }, $parameters);

        return [
            'serviceId'    => $matches['serviceId'],
            'method'     => $matches['method'],
            'parameters' => $parameters
        ];
    }
}
