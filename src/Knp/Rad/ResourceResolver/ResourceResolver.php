<?php

namespace Knp\Rad\ResourceResolver;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ResourceResolver
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolveResource($serviceId, $methodName, array $arguments)
    {
        try {
            $service = $this->container->get($serviceId);
        } catch (\Exception $e) {
            throw new \Exception(sprintf('The container could not find the service %s', $serviceId));
        }

        return call_user_func_array([$service, $methodName], $arguments);
    }
}
