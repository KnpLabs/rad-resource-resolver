<?php

namespace Knp\Rad\ResourceResolver;

interface Parser
{
    public function supports($string);
    public function parse($string);
}
