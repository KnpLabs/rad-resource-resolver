<?php

namespace Knp\Rad\ResourceResolver\Bundle;

use Knp\Rad\ResourceResolver\DependencyInjection\Compiler\ParameterCasterRegistrationPass;
use Knp\Rad\ResourceResolver\DependencyInjection\Compiler\ParserRegistrationPass;
use Knp\Rad\ResourceResolver\DependencyInjection\ResourceResolverExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ResourceResolverBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ParameterCasterRegistrationPass);
        $container->addCompilerPass(new ParserRegistrationPass);
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

