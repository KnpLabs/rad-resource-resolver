<?php

namespace Knp\Rad\ResourceResolver;

use Knp\Rad\ResourceResolver\Resource;

interface Resolver
{
    /**
     * @param Resource $configuration
     *
     * @return mixed
     */
    public function resolve(Resource $configuration);

    /**
     * @param Resource $configuration
     *
     * @return bool
     */
    public function supports(Resource $configuration);
}
