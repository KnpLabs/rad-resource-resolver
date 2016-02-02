<?php

namespace Knp\Rad\ResourceResolver;

interface ParserContainer
{
    /**
     * @param Parser $parser
     *
     * @return ParserContainer
     */
    public function addParser(Parser $parser);
}
