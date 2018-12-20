<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Checker\Eligibility;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionCouponInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;

final class PromotionCouponUsageLimitEligibilityChecker implements PromotionCouponEligibilityCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function isEligible(PromotionSubjectInterface $promotionSubject, PromotionCouponInterface $promotionCoupon): bool
    {
        $usageLimit = $promotionCoupon->getUsageLimit();

        return $usageLimit === null || $promotionCoupon->getUsed() < $usageLimit;
    }
}
