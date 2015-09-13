<?php

namespace Knp\Rad\ResourceResolver\Bundle;

use Knp\Rad\ResourceResolver\DependencyInjection\Compiler\BuildSpecificContainerPass;
use Knp\Rad\ResourceResolver\DependencyInjection\Compiler\DecorateWithDispatcherPass;
use Knp\Rad\ResourceResolver\DependencyInjection\Compiler\DisableIfServiceUnavailable;
use Knp\Rad\ResourceResolver\DependencyInjection\ResourceResolverExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class ResourceResolverBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        // depends on: HttpFoundation
        $container->addCompilerPass(new DisableIfServiceUnavailable('request_stack', 'knp_rad_resource_resolver.caster.variable_caster'));
        // depends on: HttpFoundation
        $container->addCompilerPass(new DisableIfServiceUnavailable('request_stack', 'knp_rad_resource_resolver.parser.request_based.request_config_parser'));
        // depends on: HttpFoundation / HttpKernel / EventDispatcher
        $container->addCompilerPass(new DisableIfServiceUnavailable(['request_stack', 'event_dispatcher'], 'knp_rad_resource_resolver.event_listener.resources_listener'));

        $container->addCompilerPass(new BuildSpecificContainerPass('knp_rad_resource_resolver.caster.container', 'knp_rad_resource_resolver.caster'));
        $container->addCompilerPass(new BuildSpecificContainerPass('knp_rad_resource_resolver.parser.container', 'knp_rad_resource_resolver.parser'));
        $container->addCompilerPass(new BuildSpecificContainerPass('knp_rad_resource_resolver.parser.resolver', 'knp_rad_resource_resolver.resolver'));
        $container->addCompilerPass(new DecorateWithDispatcherPass('knp_rad_resource_resolver.resource.container', 'knp_rad_resource_resolver.resource.container.traceable_container_decorator.class'));
        $container->addCompilerPass(new DecorateWithDispatcherPass('knp_rad_resource_resolver.resolver.container', 'knp_rad_resource_resolver.resolver.traceable_resolver_decorator.class'));
    }

    /**
     * {@inheritDoc}
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new ResourceResolverExtension;
        }

        return $this->extension;
    }
}
