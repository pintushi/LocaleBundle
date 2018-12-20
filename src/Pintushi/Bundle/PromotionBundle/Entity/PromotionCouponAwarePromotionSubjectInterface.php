<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Entity;

interface PromotionCouponAwarePromotionSubjectInterface extends PromotionSubjectInterface
{
    /**
     * @return PromotionCouponInterface|null
     */
    public function getPromotionCoupon(): ?PromotionCouponInterface;
}
