<?php

namespace Knp\Rad\ResourceResolver\EventListener;

use Knp\Rad\ResourceResolver\CasterContainer;
use Knp\Rad\ResourceResolver\ParameterCaster;
use Knp\Rad\ResourceResolver\Parser;
use Knp\Rad\ResourceResolver\ParserContainer;
use Knp\Rad\ResourceResolver\ResourceContainer;
use Knp\Rad\ResourceResolver\ResourceResolver;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
            $resourceDetails = $this->normalizeResourceDetails($resourceDetails);
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
                throw new NotFoundHttpException(sprintf('The resource %s could not be found', $resourceKey));
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

    private function normalizeResourceDetails($resourceDetails)
    {
        if (array_keys($resourceDetails) === array_keys(array_values($resourceDetails))) {
            if (false === strpos($resourceDetails[0], ':')) {
                throw new \RuntimeException('The first argument for a resource configuration, when expressed with a numerically indexed array, should be a string containing the service and the method used, seperated by a colon.');
            } elseif (isset($resourceDetails[1]) && !is_array($resourceDetails[1])) {
                throw new \RuntimeException('The second argument for a resource configuration, when expressed with a numerically indexed array, should be an array of arguments.');
            }

            list($service, $method) = explode(':', $resourceDetails[0]);

            return [
                'service'   => $service,
                'method'    => $method,
                'arguments' => isset($resourceDetails[1]) ? $resourceDetails[1] : [],
                'required'  => isset($resourceDetails[2]) ? (bool) $resourceDetails[2] : true,
            ];
        }

        return array_merge(['required' => true, 'arguments' => []], $resourceDetails);
    }
}
