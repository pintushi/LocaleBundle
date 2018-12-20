<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Checker\Eligibility;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionCouponAwarePromotionSubjectInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;

final class PromotionSubjectCouponEligibilityChecker implements PromotionEligibilityCheckerInterface
{
    /**
     * @var PromotionCouponEligibilityCheckerInterface
     */
    private $promotionCouponEligibilityChecker;

    /**
     * @param PromotionCouponEligibilityCheckerInterface $promotionCouponEligibilityChecker
     */
    public function __construct(PromotionCouponEligibilityCheckerInterface $promotionCouponEligibilityChecker)
    {
        $this->promotionCouponEligibilityChecker = $promotionCouponEligibilityChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function isEligible(PromotionSubjectInterface $promotionSubject, PromotionInterface $promotion): bool
    {
        if (!$promotion->isCouponBased()) {
            return true;
        }

        if (!$promotionSubject instanceof PromotionCouponAwarePromotionSubjectInterface) {
            return false;
        }

        $promotionCoupon = $promotionSubject->getPromotionCoupon();
        if (null === $promotionCoupon) {
            return false;
        }

        if ($promotion !== $promotionCoupon->getPromotion()) {
            return false;
        }

        return $this->promotionCouponEligibilityChecker->isEligible($promotionSubject, $promotionCoupon);
    }
}
