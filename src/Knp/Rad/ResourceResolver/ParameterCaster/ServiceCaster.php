<?php

namespace Knp\Rad\ResourceResolver\ParameterCaster;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceCaster
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return bool
     */
    public function supports($string)
    {
        return 1 === preg_match('/^@\w+/', $string);
    }

    /**
     * @return mixed
     */
    public function cast($string)
    {
        $service = $this->container->get($string, ContainerInterface::NULL_ON_INVALID_REFERENCE);

        return null !== $service ? $service : $string;
    }
}
