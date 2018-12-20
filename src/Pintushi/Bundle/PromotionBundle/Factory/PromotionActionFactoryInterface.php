<?php

namespace Pintushi\Component\Core\Factory;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionActionInterface;

interface PromotionActionFactoryInterface
{
    public function createFixedDiscount(int $amount, string $channelId): PromotionActionInterface;

    public function createPercentageDiscount(float $percentage): PromotionActionInterface;

    public function createShippingPercentageDiscount(float $percentage): PromotionActionInterface;
}
