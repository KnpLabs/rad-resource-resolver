<?php

namespace Knp\Rad\ResourceResolver\Resolver;

use Knp\Rad\ResourceResolver\Resolver;
use Knp\Rad\ResourceResolver\Resource;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class CallableResolver implements Resolver
{
    /**
     * {@inheritdoc}
     */
    public function resolve(Resource $configuration)
    {
        if (!$this->supports($configuration)) {
            throw new \RuntimeException('This configuration is not supported.');
        }

        return call_user_func($configuration);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Resource $configuration)
    {
        return is_callable($configuration);
    }
}
