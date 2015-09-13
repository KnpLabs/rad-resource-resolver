<?php

namespace Knp\Rad\ResourceResolver\Parser\ServiceBased;

use Knp\Rad\ResourceResolver\Caster;
use Knp\Rad\ResourceResolver\Parser;
use Knp\Rad\ResourceResolver\Resource\CallableResource;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class ImplicitConfigParser implements Parser
{
    /**
     * @var ContainerInterface
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
    public function parse($name, $config)
    {
        list($serviceName, $methodName) = explode(':', $config[0]);
        $arguments = [];
        $required  = isset($config[2]) ? boolval($config[2]) : true;

        if (isset($config[1])) {
            $arguments = $config[1];
        }

        $service = $this->container->get($serviceName);

        return new CallableResource($name, [$service, $methodName], $arguments, $required);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($config)
    {
        return is_array($config)
            && count($config) >= 1
            && array_keys($config) === array_keys(array_values($config))
            && false !== strpos($config[0], ':')
        ;
    }
}
