<?php

namespace Knp\Rad\ResourceResolver\Event;

use Knp\Rad\ResourceResolver\Resource;
use Symfony\Component\EventDispatcher\Event;

final class ResourceResolvedEvent extends Event
{
    /**
     * @var Resource
     */
    private $configuration;

    /**
     * @var mixed
     */
    private $resource;

    /**
     * @param Resource $configuration
     * @param mixed    $resource
     */
    public function __construct(Resource $configuration, $resource)
    {
        $this->configuration = $resource;
        $this->resource      = $resource;
    }

    /**
     * @return Resource
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }
}
