<?php

namespace Pintushi\Bundle\OrderBundle\NumberAssigner;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;

interface OrderNumberAssignerInterface
{
    public function assignNumber(OrderInterface $order): void;
}
