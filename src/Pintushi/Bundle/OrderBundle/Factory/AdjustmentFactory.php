<?php

namespace Pintushi\Bundle\OrderBundle\Factory;

use Pintushi\Bundle\OrderBundle\Entity\AdjustmentInterface;
use Pintushi\Bundle\OrderBundle\Entity\Adjustment;

class AdjustmentFactory implements AdjustmentFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createNew()
    {
        return new Adjustment();
    }

    /**
     * {@inheritdoc}
     */
    public function createWithData(string $type, string $label, string $amount, bool $neutral = false): AdjustmentInterface
    {
        $adjustment = $this->createNew();
        $adjustment->setType($type);
        $adjustment->setLabel($label);
        $adjustment->setAmount($amount);
        $adjustment->setNeutral($neutral);

        return $adjustment;
    }
}
