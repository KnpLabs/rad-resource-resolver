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
    public function resolve(Resource $configuration)
    {
        foreach ($this->resolvers AS $resolver) {
            if ($resolver->supports($configuration)) {
                return $resolver->resolve($configuration);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Resource $configuration)
    {
        foreach ($this->resolvers AS $resolver) {
            if ($resolver->supports($configuration)) {
                return true;
            }
        }

        return false;
    }
}
