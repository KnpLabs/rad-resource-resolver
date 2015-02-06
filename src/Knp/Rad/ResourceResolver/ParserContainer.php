<?php

namespace Knp\Rad\ResourceResolver;

use Knp\Rad\ResourceResolver\Parser;

interface ParserContainer
{
    /**
     * @param Parser $parser
     *
     * @return ParserContainer
     */
    public function addParser(Parser $parser);
}
