<?php

namespace Knp\Rad\ResourceResolver\Parser\ServiceBased;

use Knp\Rad\ResourceResolver\Caster;
use Knp\Rad\ResourceResolver\Parser;
use Knp\Rad\ResourceResolver\Resource\CallableResource;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class ExplicitConfigParser implements Parser
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
        $serviceName = $config['service'];
        $methodName  = $config['method'];
        $arguments   = [];
        $required    = isset($config['required']) ? boolval($config['required']) : true;

        if (isset($config['arguments'])) {
            $arguments = $config['arguments'];
        }

        $service = $this->container->get($serviceName);

        return new CallableResource($name, [$service, $methodName], $arguments, $required);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($config)
    {
        return is_array($config) && isset($config['service']) && isset($config['method']);
    }
}
