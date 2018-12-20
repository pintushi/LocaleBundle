<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Factory;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionCouponInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;
use Webmozart\Assert\Assert;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionCoupon;

final class PromotionCouponFactory
{
    /**
     * {@inheritdoc}
     */
    public function createNew(): PromotionCouponInterface
    {
        return new PromotionCoupon();
    }

    /**
     * {@inheritdoc}
     */
    public function createForPromotion(PromotionInterface $promotion): PromotionCouponInterface
    {
        Assert::true(
            $promotion->isCouponBased(),
            sprintf('Promotion with name %s is not coupon based.', $promotion->getName())
        );

        /** @var PromotionCouponInterface $coupon */
        $coupon = $this->createNew();
        $coupon->setPromotion($promotion);

        return $coupon;
    }
}
