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

    /**
     * @param Caster\Container $caster
     */
    public function resolveArguments(Caster\Container $caster);
}
