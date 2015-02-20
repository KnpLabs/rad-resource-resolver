<?php

namespace Knp\Rad\ResourceResolver\EventListener;

use Knp\Rad\ResourceResolver\CasterContainer;
use Knp\Rad\ResourceResolver\ParameterCaster;
use Knp\Rad\ResourceResolver\Parser;
use Knp\Rad\ResourceResolver\ParserContainer;
use Knp\Rad\ResourceResolver\ResourceContainer;
use Knp\Rad\ResourceResolver\ResourceResolver;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ResourcesListener implements CasterContainer, ParserContainer
{
    private $parsers;
    private $container;
    private $resolver;
    private $parameterCasters;

    public function __construct(ResourceResolver $resolver, ResourceContainer $container)
    {
        $this->resolver         = $resolver;
        $this->container        = $container;
        $this->parsers          = [];
        $this->parameterCasters = [];
    }

    public function resolveResources(FilterControllerEvent $event)
    {
        $request = $event->getRequest();

        $resources = [];
        foreach ($request->attributes->get('_resources', []) as $resourceKey => $resourceValue) {
            $resourceValue           = $this->parse($resourceValue) ?: $resourceValue;
            $resources[$resourceKey] = $resourceValue;
        }

        foreach ($resources as $resourceKey => $resourceDetails) {
            $parameters = [];

            foreach ($resourceDetails['arguments'] as $parameter) {
                $parameter    = $this->castParameter($parameter)?: $parameter;
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

            $this->container->addResource($resourceKey, $resource);
        }

        return $event;
    }

    /**
     * {@inheritdoc}
     */
    public function addParser(Parser $parser)
    {
        $this->parsers[] = $parser;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
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
