<?php

namespace Knp\Rad\ResourceResolver;

interface ParameterCaster
{
    /**
     * @param mixed $value
     *
     * @return boolean
     */
    public function supports($value);

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function cast($value);
}
