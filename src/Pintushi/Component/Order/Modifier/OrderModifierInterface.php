<?php

namespace Pintushi\Component\Order\Modifier;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderItemInterface;

interface OrderModifierInterface
{
    public function addToOrder(OrderInterface $cart, OrderItemInterface $cartItem): void;

    public function removeFromOrder(OrderInterface $cart, OrderItemInterface $item): void;
}
