<?php

namespace Knp\Rad\ResourceResolver\Event\ResourceEvent;

use Knp\Rad\ResourceResolver\ResourceContainer;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class ResourceEvent extends EventDispatcher
{
    /**
     * @var string
     */
    private $resourceName;

    /**
     * @var mixed
     */
    private $resource;

    /**
     * @var ResourceContainer
     */
    private $container;

    /**
     * @param string            $resourceName
     * @param mixed             $resource
     * @param ResourceContainer $container
     */
    public function __construct($resourceName, $resource, ResourceContainer $container)
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
