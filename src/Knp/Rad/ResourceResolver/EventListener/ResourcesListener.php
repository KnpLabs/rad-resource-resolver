<?php

namespace Knp\Rad\ResourceResolver\EventListener;

use Knp\Rad\ResourceResolver\Caster;
use Knp\Rad\ResourceResolver\Parser;
use Knp\Rad\ResourceResolver\Resolver;
use Knp\Rad\ResourceResolver\Resource\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ResourcesListener
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var Caster\Container
     */
    private $caster;

    /**
     * @var Resolver
     */
    private $resolver;

    /**
     * @var Container
     */
    private $container;

    /**
     * @param Parser           $parser
     * @param Caster\Container $caster
     * @param Resolver         $resolver
     * @param Container        $container
     */
    public function __construct(Parser $parser, Caster\Container $caster, Resolver $resolver, Container $container)
    {
        $this->parser    = $parser;
        $this->caster    = $caster;
        $this->resolver  = $resolver;
        $this->container = $container;
    }

    /**
     * @param FilterControllerEvent $event
     *
     * @return FilterControllerEvent
     */
    public function resolveResources(FilterControllerEvent $event)
    {
        $request        = $event->getRequest();
        $configurations = $this->getResourcesConfigurations($request);

        foreach ($configurations as $name => $configuration) {
            $configuration->resolveArguments($this->caster);
            $resource = $this->resolver->resolve($configuration);

            if ($configuration->isRequired() && null === $resource) {
                throw new NotFoundHttpException(sprintf('The resource "%s" could not be found.', $name));
            }

            $request->attributes->set($name, $resource);

            $this->container->addResource($name, $resource);
        }

        return $event;
    }

    /**
     * @param Request $request
     *
     * @return \Knp\Rad\ResourceResolver\Resource[]
     */
    private function getResourcesConfigurations(Request $request)
    {
        $resources = [];

        foreach ($request->attributes->get('_resources', []) as $name => $plainConfiguration) {
            if (!$this->parser->supports($plainConfiguration)) {
                throw new \RuntimeException('The resource config parser does not supports this type of configuration.');
            }

            $resources[$name] = $this->parser->parse($name, $plainConfiguration);
        }

        return $resources;
    }
}
