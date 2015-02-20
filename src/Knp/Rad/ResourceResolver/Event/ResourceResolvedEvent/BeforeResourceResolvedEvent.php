<?php

namespace Knp\Rad\ResourceResolver\Event\ResourceResolvedEvent;

use Knp\Rad\ResourceResolver\Event\ResourceResolvedEvent as EventInterface;
use Symfony\Component\EventDispatcher\Event;

final class BeforeResourceResolvedEvent extends Event implements EventInterface
{

    /**
     * @var mixed $resource
     */
    private $resource;

    /**
     * @var string $serviceId
     */
    private $serviceId;

    /**
     * @var object $service
     */
    private $service;

    /**
     * @var string $method
     */
    private $method;

    /**
     * @var array $arguments
     */
    private $arguments;

    /**
     * @param string $serviceId
     * @param object $service
     * @param string $method
     * @param array $arguments
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
