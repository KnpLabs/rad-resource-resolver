<?php

namespace Knp\Rad\ResourceResolver;

/**
 * Used to parse a plain configuration (string, array, or whatever) and hydrate a Resource
 */
interface Parser
{
    /**
     * @param mixed $config
     *
     * @return bool
     */
    public function supports($config);

    /**
     * @param mixed $config
     *
     * @return \Knp\Rad\ResourceResolver\Resource
     */
    public function parse($name, $config);
}
