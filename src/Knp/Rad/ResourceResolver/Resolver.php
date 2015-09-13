<?php

namespace Knp\Rad\ResourceResolver;

use Knp\Rad\ResourceResolver\Resource;

interface Resolver
{
    /**
     * @param Resource $resource
     *
     * @return mixed
     */
    public function resolve(Resource $resource);

    /**
     * @param Resource $resource
     *
     * @return bool
     */
    public function supports(Resource $resource);
}
