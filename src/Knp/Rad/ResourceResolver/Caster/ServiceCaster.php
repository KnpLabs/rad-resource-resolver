<?php

namespace Knp\Rad\ResourceResolver\Caster;

use Knp\Rad\ResourceResolver\Caster;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class ServiceCaster implements Caster
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
    public function cast($value)
    {
        return $this->container->get($value, ContainerInterface::NULL_ON_INVALID_REFERENCE);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($value)
    {
        return is_string($value) && 1 === preg_match('/^@(\w|\.)+/', $value);
    }
}
