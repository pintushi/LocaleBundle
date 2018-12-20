<?php

namespace Pintushi\Bundle\PromotionBundle\Modifier;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;

interface OrderPromotionsUsageModifierInterface
{
    public function increment(OrderInterface $order): void;

    public function decrement(OrderInterface $order): void;
}
