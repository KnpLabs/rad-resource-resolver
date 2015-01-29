<?php

namespace Knp\Rad\ResourceResolver\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Knp\Rad\ResourceResolver\Parser;
use Knp\Rad\ResourceResolver\ResourceResolver;
use Knp\Rad\ResourceResolver\ParameterCaster;

class ResourcesListener
{
    private $parsers;
    private $resolver;
    private $parameterCasters;

    public function __construct(ResourceResolver $resolver)
    {
        $this->resolver         = $resolver;
        $this->parsers          = [];
        $this->parameterCasters = [];
    }

    public function resolveResources(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        $resources = [];
        foreach ($request->attributes->get('_resources') as $resourceKey => $resourceValue) {
            $resourceValue           = $this->parse($resourceValue) ?: $resourceValue;
            $resources[$resourceKey] = $resourceValue;
        }

        foreach ($resources as $resourceKey => $resourceDetails) {
            $parameters = [];

            foreach ($resourceDetails['arguments'] as $parameter) {
                $parameter = $this->castParameter($parameter)?: $parameter;
                $parameters[] = $parameter;
            }

            $resource = $this
                ->resolver
                ->resolveResource(
                    $resourceDetails['service'],
                    $resourceDetails['method'],
                    $parameters
                )
            ;

            $request->attributes->set($resourceKey, $resource);
        }

        return $event;
    }

    public function addParser(Parser $parser)
    {
        $this->parsers[] = $parser;

        return $this;
    }

    public function addParameterCaster(ParameterCaster $parameterCaster)
    {
        $this->parameterCasters[] = $parameterCaster;

        return $this;
    }

    /**
     * @return null|array
     */
    protected function parse($resourceDetails)
    {
        foreach ($this->parsers as $parser) {
            if (true === $parser->supports($resourceDetails)) {
                return $parser->parse($resourceDetails);
            }
        }
    }

    /**
     * @return mixed
     */
    protected function castParameter($parameter)
    {
        foreach ($this->parameterCasters as $parameterCaster) {
            if (true === $parameterCaster->supports($parameter)) {
                return $parameterCaster->cast($parameter);
            }
        }
    }
}
