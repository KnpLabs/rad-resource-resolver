<?php

namespace Knp\Rad\ResourceResolver;

interface CasterContainer
{
    /**
     * @param ParameterCaster $parameterCaster
     *
     * @return CasterContainer
     */
    public function addParameterCaster(ParameterCaster $parameterCaster);
}
