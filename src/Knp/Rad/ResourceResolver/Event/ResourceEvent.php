<?php

namespace Knp\Rad\ResourceResolver\Event;

use Knp\Rad\ResourceResolver\Resource\Container;
use Symfony\Component\EventDispatcher\Event;

final class ResourceEvent extends Event
{
    /**
     * @var string $resourceName
     */
    private $resourceName;

    /**
     * @var mixed $resource
     */
    private $resource;

    /**
     * @var Container $container
     */
    private $container;

    /**
     * @param string    $resourceName
     * @param mixed     $resource
     * @param Container $container
     */
    public function __construct($resourceName, $resource, Container $container)
    {
        $this->resourceName = $resourceName;
        $this->resource     = $resource;
        $this->container    = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->resourceName;
    }

    /**
     * {@inheritdoc}
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * {@inheritdoc}
     */
    public function getContainer()
    {
        return $this->container;
    }
}
