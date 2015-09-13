<?php

namespace Knp\Rad\ResourceResolver\Caster;

use Knp\Rad\ResourceResolver\Caster;
use Knp\Rad\ResourceResolver\Resource;

final class ResourceCaster implements Caster
{
    /**
     * @var Resource\Container
     */
    private $container;

    /**
     * @param Resource\Container $container
     */
    public function __construct(Resource\Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function cast($value)
    {
        return $this->container->getResource(substr($value, 1));
    }

    /**
     * {@inheritdoc}
     */
    public function supports($value)
    {
        return is_string($value) && 1 === preg_match('/^\&[a-zA-Z_]+/', $value);
    }
}
