<?php

namespace Knp\Rad\ResourceResolver\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ParserRegistrationPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('knp_rad_resource_resolver.parser_container') as $registryId => $registryTags) {
            $registry = $container->getDefinition($registryId);

            foreach ($container->findTaggedServiceIds('knp_rad_resource_resolver.parser') as $parserId => $parserTags) {
                $registry->addMethodCall('addParser', [new Reference($parserId)]);
            }
        }
    }
}
