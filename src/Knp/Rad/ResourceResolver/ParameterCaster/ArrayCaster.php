<?php

namespace Knp\Rad\ResourceResolver\ParameterCaster;

use Knp\Rad\ResourceResolver\CasterContainer;
use Knp\Rad\ResourceResolver\ParameterCaster;

class ArrayCaster implements ParameterCaster, CasterContainer
{
    /**
     * @var ParameterCaster[]
     */
    private $parameterCasters;

    /**
     * {@inheritdoc}
     */
    public function supports($array)
    {
        return true === is_array($array);
    }

    /**
     * {@inheritdoc}
     */
    public function cast($array)
    {
        foreach ($array as $key => $value) {
            foreach ($this->parameterCasters as $caster) {
                if ($caster->supports($value)) {
                    $value = $caster->cast($value);
                    continue;
                }
            }

            $array[$key] = $value;
        }

        return $array;
    }

    /**
     * {@inheritdoc}
     */
    public function addParameterCaster(ParameterCaster $parameterCaster)
    {
        $this->parameterCasters[] = $parameterCaster;

        return $this;
    }
}
