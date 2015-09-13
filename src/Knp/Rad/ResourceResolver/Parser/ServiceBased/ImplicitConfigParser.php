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
     * @var Caster\Container
     */
    private $casters;

    /**
     * @param ContainerInterface $container
     * @param Caster\Container   $casters
     */
    public function __construct(ContainerInterface $container, Caster\Container $casters)
    {
        $this->container = $container;
        $this->casters   = $casters;
    }

    /**
     * {@inheritdoc}
     */
    public function parse($name, $config)
    {
        list($serviceName, $methodName) = explode(':', $config[0]);
        $arguments = [];
        $required  = isset($config['required']) ? boolval($config['required']) : true;

        $service = $this->container->get($serviceName);

        if (isset($config['arguments'])) {
            $arguments = $this->casters->cast($config['arguments']);
        }

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
