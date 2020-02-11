<?php

namespace Knp\Rad\ResourceResolver;

use Knp\Rad\ResourceResolver\Event\ResourceResolvedEvent\BeforeResourceResolvedEvent;
use Knp\Rad\ResourceResolver\Event\ResourceResolvedEvent\ResourceResolvedEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ResourceResolver
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param ContainerInterface            $container
     * @param EventDispatcherInterface|null $dispatcher
     */
    public function __construct(ContainerInterface $container, EventDispatcherInterface $dispatcher = null)
    {
        $this->container  = $container;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param string $serviceId
     * @param string $methodName
     * @param array  $arguments
     *
     * @return mixed
     */
    public function resolveResource($serviceId, $methodName, array $arguments)
    {
        try {
            $service = $this->container->get($serviceId);
        } catch (ServiceNotFoundException $e) {
            throw $e;
        }

        $event = new BeforeResourceResolvedEvent($serviceId, $service, $methodName, $arguments);

        if (null !== $this->dispatcher) {
            $this->dispatcher->dispatch($event,Events::BEFORE_RESOURCE_RESOLVED);
        }

        if (null === $event->getResource()) {
            $callable = $methodName ? [$service, $methodName] : $service;
            $resource = call_user_func_array($callable, $arguments);
            $event->setResource($resource);
        }

        if (null !== $this->dispatcher) {
            $this->dispatcher->dispatch(new ResourceResolvedEvent($event),Events::RESOURCE_RESOLVED);
        }

        return $event->getResource();
    }
}
