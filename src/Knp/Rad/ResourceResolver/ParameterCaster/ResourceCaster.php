<?php

namespace Knp\Rad\ResourceResolver\ParameterCaster;

use Knp\Rad\ResourceResolver\ParameterCaster;
use Knp\Rad\ResourceResolver\ResourceContainer;

class ResourceCaster implements ParameterCaster
{
    /**
     * @var ResourceContainer
     */
    private $container;

    /**
     * @param ResourceContainer $container
     */
    public function __construct(ResourceContainer $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($value)
    {
        if (false === is_string($value)) {
            return false;
        }

        return 1 === preg_match('/^\&[a-zA-Z_]+/', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function cast($value)
    {
        return $this->container->getResource(substr($value, 1));
    }
}
