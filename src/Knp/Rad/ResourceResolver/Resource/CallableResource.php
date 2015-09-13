<?php

namespace Knp\Rad\ResourceResolver\Resource;

use Knp\Rad\ResourceResolver\Caster;
use Knp\Rad\ResourceResolver\Resource;

final class CallableResource implements Resource
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var callable
     */
    private $callable;

    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @var bool
     */
    private $required;

    /**
     * @var array
     */
    private $resolvedArguments = [];

    /**
     * @param string   $name
     * @param callable $callable
     * @param array    $arguments
     * @param bool     $required
     */
    public function __construct($name, callable $callable, $arguments = [], $required = true)
    {
        $this->name      = $name;
        $this->callable  = $callable;
        $this->arguments = $arguments;
        $this->required  = $required;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function isRequired()
    {
        return $this->required;
    }

    public function resolveArguments(Caster\Container $caster)
    {
        foreach ($this->arguments as $argument) {
            if (1 !== preg_match('/^[a-zA-Z]+$/', $argument)) {
                $argument = $caster->cast($argument);
            }

            $this->resolvedArguments[] = $argument;
        }
    }

    public function __invoke()
    {
        if (count($this->resolvedArguments) != count($this->arguments)) {
            throw new \RuntimeException('Some arguments are not resolved.');
        }

        return call_user_func_array($this->callable, $this->resolvedArguments);
    }
}
