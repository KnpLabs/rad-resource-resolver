<?php

namespace Knp\Rad\ResourceResolver\Bundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Knp\Rad\ResourceResolver\DependencyInjection\ResourceResolverExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Knp\Rad\ResourceResolver\DependencyInjection\Compiler\SyntaxParserPass;

class ResourceResolverBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SyntaxParserPass);
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

