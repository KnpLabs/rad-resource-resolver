<?php

namespace Knp\Rad\ResourceResolver\Event;

use Knp\Rad\ResourceResolver\Resource;
use Symfony\Component\EventDispatcher\Event;

final class BeforeResourceResolvedEvent extends Event
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
     */
    public function __construct(Resource $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return Resource
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /***
     * @param $resource
     *
     * @return BeforeResourceResolvedEvent
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }
}
