<?php

namespace Knp\Rad\ResourceResolver\Resource;

interface Container extends \ArrayAccess
{
    /**
     * @return array
     */
    public function getResources();

    /**
     * @param string $key
     *
     * @return mixed
     *
     * @throw \DomainException if resource doesn't exists
     */
    public function getResource($key);

    /**
     * @param string $key
     *
     * @return boolean
     */
    public function hasResource($key);

    /**
     * @param string $key
     * @param mixed $resource
     *
     * @return Container
     */
    public function addResource($key, $resource);

    /**
     * @param string $key
     *
     * @return Container
     *
     * @throw \DomainException if resource doesn't exists
     */
    public function removeResource($key);
}
