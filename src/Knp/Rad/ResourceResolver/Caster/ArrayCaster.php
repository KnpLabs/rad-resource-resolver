<?php

namespace Knp\Rad\ResourceResolver\Caster;

use Knp\Rad\ResourceResolver\Caster;

final class ArrayCaster implements Caster
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function cast($array)
    {
        foreach ($array as $key => $value) {
            $array[$key] = $this->container->cast($value);
        }

        return $array;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($array)
    {
        return true === is_array($array);
    }
}
