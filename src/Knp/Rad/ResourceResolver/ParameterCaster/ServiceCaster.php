<?php

namespace Knp\Rad\ResourceResolver\ParameterCaster;

use Knp\Rad\ResourceResolver\ParameterCaster;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceCaster implements ParameterCaster
{
    /**
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
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

        return 1 === preg_match('/^@(\w|\.)+/', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function cast($value)
    {
        $service = $this->container->get($value, ContainerInterface::NULL_ON_INVALID_REFERENCE);

        return null !== $service ? $service : $value;
    }
}
