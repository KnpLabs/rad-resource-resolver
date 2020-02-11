<?php

namespace Knp\Rad\ResourceResolver\Event\ResourceResolvedEvent;

use Knp\Rad\ResourceResolver\Event\ResourceResolvedEvent as EventInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class BeforeResourceResolvedEvent extends Event implements EventInterface
{
    /**
     * @var mixed
     */
    private $resource;

    /**
     * @var string
     */
    private $serviceId;

    /**
     * @var object
     */
    private $service;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @param string $serviceId
     * @param object $service
     * @param string $method
     * @param array  $arguments
     */
    public function __construct($serviceId, $service, $method, array $arguments)
    {
        $this->serviceId = $serviceId;
        $this->service   = $service;
        $this->method    = $method;
        $this->arguments = $arguments;
    }

    /**
     * {@inheritdoc}
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param mixed $resource
     *
     * @return BeforeResourceResolvedEvent
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        if (null !== $resource) {
            $this->stopPropagation();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceId()
    {
        return $this->serviceId;
    }

    /**
     * {@inheritdoc}
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return $this->arguments;
    }
}
