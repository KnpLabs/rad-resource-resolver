<?php

namespace Knp\Rad\ResourceResolver;

interface ResourceContainer extends \ArrayAccess
{
    /**
     * @return array
     */
    public function getResources();

    /**
     * @param string $key
     *
     * @return mixed
     * @throw \DomainException if resource doesn't exists
     */
    public function getResource($key);

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasResource($key);

    /**
     * @param string $key
     * @param mixed  $resource
     *
     * @return ResourceContainer
     */
    public function addResource($key, $resource);

    /**
     * @param string $key
     *
     * @return ResourceContainer
     * @throw \DomainException if resource doesn't exists
     */
    public function removeResource($key);
}
