<?php

namespace Knp\Rad\ResourceResolver\ResourceContainer;

use Knp\Rad\ResourceResolver\ResourceContainer as ContainerInterface;

class ResourceContainer implements ContainerInterface
{
    /**
     * @var array
     */
    private $resources;

    /**
     * @param array $resources
     */
    public function __construct(array $resources = [])
    {
        $this->resources = $resources;
    }

    /**
     * {@inheritdoc}
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * {@inheritdoc}
     */
    public function getResource($key)
    {
        $this->resourceExistsOrException($key);

        return $this->resources[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function hasResource($key)
    {
        return array_key_exists($key, $this->resources);
    }

    /**
     * {@inheritdoc}
     */
    public function addResource($key, $resource)
    {
        $this->resources[$key] = $resource;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeResource($key)
    {
        $this->resourceExistsOrException($key);

        unset($this->resources[$key]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return $this->hasResource($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->getResource($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        return $this->addResource($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        return $this->removeResource($offset);
    }

    private function resourceExistsOrException($key)
    {
        if (false === $this->hasResource($key)) {
            throw new \DomainException(sprintf(
                'Resource "%s" not found, "%s" available.',
                $key,
                implode('", "', array_keys($this->resources))
            ));
        }
    }
}
