<?php

namespace Pintushi\Bundle\PromotionBundle\Applicator;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;

interface OrderItemsPromotionAdjustmentsApplicatorInterface
{
    /**
     * @param OrderInterface $order
     * @param PromotionInterface $promotion
     * @param array $adjustmentsAmounts
     */
    public function apply(OrderInterface $order, PromotionInterface $promotion, array $adjustmentsAmounts): void;
}
