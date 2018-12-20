<?php

namespace Pintushi\Bundle\PromotionBundle\Modifier;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;

final class OrderPromotionsUsageModifier implements OrderPromotionsUsageModifierInterface
{
    /**
     * {@inheritdoc}
     */
    public function increment(OrderInterface $order): void
    {
        foreach ($order->getPromotions() as $promotion) {
            $promotion->incrementUsed();
        }

        $promotionCoupon = $order->getPromotionCoupon();
        if (null !== $promotionCoupon) {
            $promotionCoupon->incrementUsed();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function decrement(OrderInterface $order): void
    {
        foreach ($order->getPromotions() as $promotion) {
            $promotion->decrementUsed();
        }

        $promotionCoupon = $order->getPromotionCoupon();
        if (null !== $promotionCoupon) {
            $promotionCoupon->decrementUsed();
        }
    }
}
