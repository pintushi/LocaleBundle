<?php

namespace Pintushi\Component\Order\Modifier;

use Pintushi\Bundle\OrderBundle\Entity\OrderItemInterface;

interface OrderItemQuantityModifierInterface
{
    public function modify(OrderItemInterface $orderItem, int $targetQuantity): void;
}
