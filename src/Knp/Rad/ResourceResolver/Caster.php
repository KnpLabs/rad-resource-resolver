<?php

namespace Knp\Rad\ResourceResolver;

/**
 * Used by parsers to cast arguments from resource plain configuration to fully resolved resource configuration
 */
interface Caster
{
    /**
     * @param mixed $value
     *
     * @return mixed|null If the cast failed, it will returns `null`
     */
    public function cast($value);

    /**
     * @param mixed $value
     *
     * @return boolean
     */
    public function supports($value);
}
