<?php

namespace Knp\Rad\ResourceResolver\Event\ResourceEvent;

use Knp\Rad\ResourceResolver\Event\ResourceEvent;
use Knp\Rad\ResourceResolver\Event\ResourceEvent\BeforeResourceResolvedEvent;
use Symfony\Component\EventDispatcher\Event;

final class ResourceResolvedEvent extends Event implements ResourceEvent
{
    /**
     * @var BeforeResourceResolvedEvent $event
     */
    private $event;

    /**
     * @param BeforeResourceResolvedEvent $event
     */
    public function __construct(BeforeResourceResolvedEvent $event)
    {
        $this->event = $event;
    }

    /**
     * {@inheritdoc}
     */
    public function getResource()
    {
        return $this->event->getResource();
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceId()
    {
        return $this->event->getServiceId();
    }

    /**
     * {@inheritdoc}
     */
    public function getService()
    {
        return $this->event->getService();
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return $this->event->getMethod();
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return $this->event->getArguments();
    }
}
