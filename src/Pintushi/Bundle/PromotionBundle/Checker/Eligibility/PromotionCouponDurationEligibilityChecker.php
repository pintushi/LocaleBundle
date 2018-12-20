<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Checker\Eligibility;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionCouponInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;

final class PromotionCouponDurationEligibilityChecker implements PromotionCouponEligibilityCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function isEligible(PromotionSubjectInterface $promotionSubject, PromotionCouponInterface $promotionCoupon): bool
    {
        $endsAt = $promotionCoupon->getExpiresAt();

        return $endsAt === null || new \DateTime() < $endsAt;
    }
}
