<?php

namespace Knp\Rad\ResourceResolver\Event;

interface ResourceEvent
{
    /**
     * @return mixed
     */
    public function getResource();

    /**
     * @return string
     */
    public function getServiceId();

    /**
     * @return object
     */
    public function getService();

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return array
     */
    public function getArguments();
}
