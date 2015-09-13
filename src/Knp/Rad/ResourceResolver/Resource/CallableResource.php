<?php

namespace Knp\Rad\ResourceResolver\Resource;

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
    private $arguments;

    /**
     * @var bool
     */
    private $required;

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

    public function __invoke()
    {
        return call_user_func($this->callable, $this->arguments);
    }
}
