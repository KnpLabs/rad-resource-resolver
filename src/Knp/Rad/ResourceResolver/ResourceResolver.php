<?php

namespace Knp\Rad\ResourceResolver;

use Knp\Rad\ResourceResolver\Event\ResourceEvent\BeforeResourceResolvedEvent;
use Knp\Rad\ResourceResolver\Event\ResourceEvent\ResourceResolvedEvent;
use Knp\Rad\ResourceResolver\Events;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ResourceResolver
{
    /**
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * @var EventDispatcherInterface $dispatcher
     */
    private $dispatcher;

    /**
     * @param ContainerInterface $container
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
     * @param array $arguments
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
            $this->dispatcher->dispatch(Events::BEFORE_RESOURCE_RESOLVED, $event);
        }

        if (null === $event->getResource()) {
            $resource = call_user_func_array([$service, $methodName], $arguments);
            $event->setResource($resource);
        }

        if (null !== $this->dispatcher) {
            $this->dispatcher->dispatch(Events::RESOURCE_RESOLVED, new ResourceResolvedEvent($event));
        }

        return $event->getResource();
    }
}
