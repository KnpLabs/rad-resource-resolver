<?php

namespace Knp\Rad\ResourceResolver;

interface ParameterCaster
{
    public function supports($string);
    public function cast($string);
}
