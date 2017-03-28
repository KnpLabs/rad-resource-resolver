<?php

namespace Knp\Rad\ResourceResolver\EventListener;

use Knp\Rad\ResourceResolver\CasterContainer;
use Knp\Rad\ResourceResolver\ParameterCaster;
use Knp\Rad\ResourceResolver\Parser;
use Knp\Rad\ResourceResolver\ParserContainer;
use Knp\Rad\ResourceResolver\ResourceContainer;
use Knp\Rad\ResourceResolver\ResourceResolver;
use Knp\Rad\ResourceResolver\RoutingNormalizer;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResourcesListener implements CasterContainer, ParserContainer
{
    /** @var Parser[] */
    private $parsers;

    /** @var ResourceContainer */
    private $container;

    /** @var ResourceResolver */
    private $resolver;

    /** @var ParameterCaster[] */
    private $parameterCasters;

    /** @var RoutingNormalizer */
    private $normalizer;

    public function __construct(ResourceResolver $resolver, ResourceContainer $container, RoutingNormalizer $normalizer)
    {
        $this->resolver         = $resolver;
        $this->container        = $container;
        $this->parsers          = [];
        $this->parameterCasters = [];
        $this->normalizer       = $normalizer;
    }

    public function resolveResources(FilterControllerEvent $event)
    {
        $request   = $event->getRequest();
        $resources = [];

        foreach ($request->attributes->get('_resources', []) as $resourceKey => $resourceValue) {
            $resourceValue           = $this->parse($resourceValue) ?: $resourceValue;
            $resources[$resourceKey] = $resourceValue;
        }

        foreach ($resources as $resourceKey => $resourceDetails) {
            $resourceDetails = $this->normalizer->normalizeDeclaration($resourceDetails);
            $parameters      = [];

            foreach ($resourceDetails['arguments'] as $parameter) {
                $parameter    = $this->castParameter($parameter) ?: $parameter;
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

            if (false !== $resourceDetails['required'] && null === $resource) {
                if (!array_key_exists('on-missing', $resourceDetails)) {
                    throw new NotFoundHttpException(sprintf('The resource %s could not be found', $resourceKey));
                }

                if (!is_array($resourceDetails['on-missing']) || !array_key_exists('throw', $resourceDetails['on-missing'])) {
                    throw new \InvalidArgumentException('"on-missing" must be an array and contain "throw" parameter.');
                }
                $this->throwMissingException($resourceDetails['on-missing'], $resourceKey);
            }

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

    private function throwMissingException(array $configuration, $resourceKey)
    {
        if (!in_array($configuration['throw'], array_keys($this->exceptions))) {
            throw new \InvalidArgumentException(sprintf(
                '%s is not an available HTTP return code. Available are %s.',
                $configuration['throw'],
                implode(', ', array_keys($this->exceptions))
            ));
        }

        $message = sprintf('The resource %s was not found.', $resourceKey);

        throw new $this->exceptions[$configuration['throw']]($message);
    }

    /**
     * @return null|array
     */
    private function parse($resourceDetails)
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
    private function castParameter($parameter)
    {
        foreach ($this->parameterCasters as $parameterCaster) {
            if (true === $parameterCaster->supports($parameter)) {
                return $parameterCaster->cast($parameter);
            }
        }
    }
}
