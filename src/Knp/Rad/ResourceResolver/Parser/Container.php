<?php

namespace Knp\Rad\ResourceResolver\Parser;

use Knp\Rad\ResourceResolver\Parser;

final class Container implements Parser
{
    /**
     * @var Parser[]
     */
    private $parsers = [];

    /**
     * @param Parser $parser
     *
     * @return Container
     */
    public function addParser(Parser $parser)
    {
        $this->parsers[] = $parser;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function parse($name, $plainConfiguration)
    {
        foreach ($this->parsers AS $parser) {
            if ($parser->supports($plainConfiguration)) {
                return $parser->parse($name, $plainConfiguration);
            }
        }

        throw new \RuntimeException('No parser supports this plain resource configuration.');
    }

    /**
     * {@inheritdoc}
     */
    public function supports($plainConfiguration)
    {
        foreach ($this->parsers AS $parser) {
            if ($parser->supports($plainConfiguration)) {
                return true;
            }
        }

        return false;
    }
}
