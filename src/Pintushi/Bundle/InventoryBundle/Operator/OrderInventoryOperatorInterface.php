<?php

namespace Pintushi\Bundle\InventoryBundle\Operator;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;

interface OrderInventoryOperatorInterface
{
    public function cancel(OrderInterface $order): void;

    public function hold(OrderInterface $order): void;

    public function sell(OrderInterface $order): void;
}
