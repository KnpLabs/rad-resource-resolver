<?php

namespace Knp\Rad\ResourceResolver\Caster;

use Knp\Rad\ResourceResolver\Caster;

final class Container
{
    /**
     * @var Caster[]
     */
    private $casters = [];

    /**
     * @param Caster $caster
     */
    public function addCaster(Caster $caster)
    {
        $this->casters[] = $caster;
    }

    /**
     * @param mixed $parameter
     *
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function cast($parameter)
    {
        foreach ($this->casters AS $caster) {
            if ($caster->supports($parameter)) {
                return $caster->cast($parameter);
            }
        }

        throw new \RuntimeException('No parameter caster supports this parameter.');
    }
}
