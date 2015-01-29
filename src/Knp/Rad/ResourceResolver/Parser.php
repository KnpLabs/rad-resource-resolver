<?php

namespace Knp\Rad\ResourceResolver;

interface Parser
{
    /**
     * @return bool
     */
    public function supports($string);

    /**
     * @return array
     */
    public function parse($string);
}
