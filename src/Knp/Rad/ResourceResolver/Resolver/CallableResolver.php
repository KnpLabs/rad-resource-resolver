<?php

namespace Knp\Rad\ResourceResolver\Resolver;

use Knp\Rad\ResourceResolver\Resolver;
use Knp\Rad\ResourceResolver\Resource;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class CallableResourceResolver implements Resolver
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
    public function resolve(Resource $resource)
    {
        if (!$this->supports($resource)) {
            throw new \RuntimeException('This configuration is not supported.');
        }

        return call_user_func($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Resource $resource)
    {
        return is_callable($resource);
    }
}
