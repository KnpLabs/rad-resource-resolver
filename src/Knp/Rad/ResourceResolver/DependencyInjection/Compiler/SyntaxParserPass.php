<?php

namespace Knp\Rad\ResourceResolver\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SyntaxParserPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $parser = $container->getDefinition('knp_rad_resource_resolver.syntax_parser');

        foreach ($container->findTaggedServiceIds('knp_rad_resource_resolver.parameter_resolver') as $id => $tags) {
            $parser->addMethodCall('addParameterResolver', [new Reference($id)]);
        }
    }
}
