<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Checker\Eligibility;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionCouponInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;

interface PromotionCouponEligibilityCheckerInterface
{
    /**
     * @param PromotionSubjectInterface $promotionSubject
     * @param PromotionCouponInterface $promotionCoupon
     *
     * @return bool
     */
    public function isEligible(PromotionSubjectInterface $promotionSubject, PromotionCouponInterface $promotionCoupon): bool;
}
