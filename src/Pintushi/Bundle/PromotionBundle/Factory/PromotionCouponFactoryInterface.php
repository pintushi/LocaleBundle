<?php


declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Factory;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionCouponInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;

interface PromotionCouponFactoryInterface
{
    /**
     * @param PromotionInterface $promotionId
     *
     * @return PromotionCouponInterface
     *
     * @throws \InvalidArgumentException
     */
    public function createForPromotion(PromotionInterface $promotionId): PromotionCouponInterface;
}
