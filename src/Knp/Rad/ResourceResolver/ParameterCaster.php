<?php

namespace Knp\Rad\ResourceResolver;

interface ParameterCaster
{
    /**
     * @return bool
     */
    public function supports($string);

    /**
     * @return mixed
     */
    public function cast($string);
}
