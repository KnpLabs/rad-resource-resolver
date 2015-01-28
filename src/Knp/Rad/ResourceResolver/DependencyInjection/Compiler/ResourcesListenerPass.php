<?php

namespace Knp\Rad\ResourceResolver\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ResourcesListenerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        try {
            $parser = $container->getDefinition('knp_rad_resource_resolver.event_listener.resources_listener');
        } catch (\Exception $e) {
            throw new \Exception('The service knp_rad_resource_resolver.event_listener.resources_listener was not found.');
        }

        foreach ($container->findTaggedServiceIds('knp_rad_resource_resolver.parser') as $id => $tags) {
            $parser->addMethodCall('addParser', [new Reference($id)]);
        }

        foreach ($container->findTaggedServiceIds('knp_rad_resource_resolver.parameter_caster') as $id => $tags) {
            $parser->addMethodCall('addParameterCaster', [new Reference($id)]);
        }
    }
}
