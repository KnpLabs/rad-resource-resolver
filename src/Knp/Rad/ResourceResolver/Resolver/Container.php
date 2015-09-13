<?php

namespace Knp\Rad\ResourceResolver\Resolver;

use Knp\Rad\ResourceResolver\Resolver;
use Knp\Rad\ResourceResolver\Resource;

final class Container implements Resolver
{
    /**
     * @var Resolver[]
     */
    private $resolvers = [];

    /**
     * @param Resolver $resolver
     *
     * @return Container
     */
    public function addResolver(Resolver $resolver)
    {
        $this->resolvers[] = $resolver;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Resource $resource)
    {
        foreach ($this->resolvers AS $resolver) {
            if ($resolver->supports($resource)) {
                return $resolver->resolve($resource);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Resource $resource)
    {
        foreach ($this->resolvers AS $resolver) {
            if ($resolver->supports($resource)) {
                return true;
            }
        }

        return false;
    }
}
