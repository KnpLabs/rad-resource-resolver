<?php

namespace Knp\Rad\ResourceResolver\Event;

interface ResourceEvent
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return mixed
     */
    public function getResource();

    /**
     * @return Knp\Rad\ResourceResolver\ResourceContainer
     */
    public function getContainer();
}
