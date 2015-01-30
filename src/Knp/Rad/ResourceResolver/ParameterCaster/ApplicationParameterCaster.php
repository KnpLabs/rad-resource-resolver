<?php

namespace Knp\Rad\ResourceResolver\ParameterCaster;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class ApplicationParameterCaster
{
    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    /**
     * @return bool
     */
    public function supports($string)
    {
        return 1 === preg_match('/^%(\w|\.)+%$/', $string);
    }

    /**
     * @return mixed
     */
    public function cast($string)
    {
        try {
            return $this->container->getParameter($string);
        } catch (InvalidArgumentException $exception) {
            return $string;
        }
    }
}
