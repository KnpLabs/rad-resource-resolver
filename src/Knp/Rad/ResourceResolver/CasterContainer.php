<?php

namespace Knp\Rad\ResourceResolver;

use Knp\Rad\ResourceResolver\ParameterCaster;

interface CasterContainer
{
    /**
     * @param ParameterCaster $parameterCaster
     *
     * @return CasterContainer
     */
    public function addParameterCaster(ParameterCaster $parameterCaster);
}
