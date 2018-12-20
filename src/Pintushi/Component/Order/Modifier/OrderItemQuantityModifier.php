<?php

namespace Pintushi\Component\Order\Modifier;

use Pintushi\Component\Order\Factory\OrderItemUnitFactoryInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderItemInterface;

class OrderItemQuantityModifier implements OrderItemQuantityModifierInterface
{
    /**
     * {@inheritdoc}
     */
    public function modify(OrderItemInterface $orderItem, int $targetQuantity): void
    {
        $currentQuantity = $orderItem->getQuantity();
        if (0 >= $targetQuantity || $currentQuantity === $targetQuantity) {
            return;
        }

        $orderItem->setQuantity($targetQuantity);
    }
}
