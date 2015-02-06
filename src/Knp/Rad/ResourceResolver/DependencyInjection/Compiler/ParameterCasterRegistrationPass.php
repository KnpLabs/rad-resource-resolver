<?php

namespace Knp\Rad\ResourceResolver\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ParameterCasterRegistrationPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('knp_rad_resource_resolver.parameter_caster_container') as $registryId => $registryTags) {
            $registry = $container->getDefinition($registryId);

            foreach ($container->findTaggedServiceIds('knp_rad_resource_resolver.parameter_caster') as $parameterCasterId => $parameterCasterTags) {
                $registry->addMethodCall('addParameterCaster', [new Reference($parameterCasterId)]);
            }
        }
    }
}
