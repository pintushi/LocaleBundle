<?php

namespace Pintushi\Bundle\OrderBundle\Factory;

use Pintushi\Bundle\OrderBundle\Entity\AdjustmentInterface;

interface AdjustmentFactoryInterface
{
    public function createWithData(string $type, string $label, string $amount, bool $neutral = false): AdjustmentInterface;
}
