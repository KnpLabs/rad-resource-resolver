<?php

namespace Knp\Rad\ResourceResolver\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Knp\Rad\ResourceResolver\Parser\Parser;
use Knp\Rad\ResourceResolver\ResourceResolver;

class ResourcesListener
{
    private $parser;
    private $resolver;

    public function __construct(Parser $parser, ResourceResolver $resolver)
    {
        $this->parser   = $parser;
        $this->resolver = $resolver;
    }

    public function resolveResources(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        $resources = $request->attributes->get('_resources');

        foreach ($resources as $resourceKey => $resourceSyntax) {
            $path = $this->parser->parse($resourceSyntax);
            $resource = $this
                ->resolver
                ->resolveResource(
                    $path['serviceId'],
                    $path['method'],
                    $path['parameters']
                )
            ;

            $request->attributes->set($resourceKey, $resource);
        }

        return $event;
    }
}
