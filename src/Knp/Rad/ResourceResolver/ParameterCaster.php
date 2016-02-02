<?php

namespace Knp\Rad\ResourceResolver;

interface ParameterCaster
{
    /**
     * @param mixed $value
     *
     * @return bool
     */
    public function supports($value);

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function cast($value);
}
