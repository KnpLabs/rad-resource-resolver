<?php

namespace Knp\Rad\ResourceResolver\Resolver;

use Knp\Rad\ResourceResolver\Event\BeforeResourceResolvedEvent;
use Knp\Rad\ResourceResolver\Event\ResourceResolvedEvent\ResourceResolvedEvent;
use Knp\Rad\ResourceResolver\Events;
use Knp\Rad\ResourceResolver\Resource;
use Knp\Rad\ResourceResolver\Resolver;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @TODO: should be renamed ?
 */
final class TraceableResolverDecorator implements Resolver
{
    /**
     * @var Resolver
     */
    private $wrapped;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param Resolver                 $wrapped
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(Resolver $wrapped, EventDispatcherInterface $dispatcher)
    {
        $this->wrapped    = $wrapped;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Resource $resource)
    {
        $resolved    = null;
        $beforeEvent = new BeforeResourceResolvedEvent($resource);
        $this->dispatcher->dispatch(Events::BEFORE_RESOURCE_RESOLVED, $beforeEvent);

        if ($beforeEvent->isPropagationStopped()) {
            $resolved = $beforeEvent->getResource();
        } else {
            $resolved = $this->wrapped->resolve($resource);

            $afterEvent = new ResourceResolvedEvent($resource, $resolved);
            $this->dispatcher->dispatch(Events::RESOURCE_RESOLVED, $afterEvent);

            $resolved = $afterEvent->getResource();
        }

        return $resolved;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Resource $resource)
    {
        return $this->wrapped->supports($resource);
    }
}
