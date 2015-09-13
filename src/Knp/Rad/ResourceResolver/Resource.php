<?php

namespace Knp\Rad\ResourceResolver;

interface Resource
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return bool
     */
    public function isRequired();
}
