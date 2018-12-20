<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Checker\Eligibility;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionCouponInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;
use Webmozart\Assert\Assert;

final class CompositePromotionCouponEligibilityChecker implements PromotionCouponEligibilityCheckerInterface
{
    /**
     * @var PromotionCouponEligibilityCheckerInterface[]
     */
    private $promotionCouponEligibilityCheckers;

    /**
     * @param PromotionCouponEligibilityCheckerInterface[] $promotionCouponEligibilityCheckers
     */
    public function __construct(array $promotionCouponEligibilityCheckers)
    {
        Assert::notEmpty($promotionCouponEligibilityCheckers);
        Assert::allIsInstanceOf($promotionCouponEligibilityCheckers, PromotionCouponEligibilityCheckerInterface::class);

        $this->promotionCouponEligibilityCheckers = $promotionCouponEligibilityCheckers;
    }

    /**
     * {@inheritdoc}
     */
    public function isEligible(PromotionSubjectInterface $promotionSubject, PromotionCouponInterface $promotionCoupon): bool
    {
        foreach ($this->promotionCouponEligibilityCheckers as $promotionCouponEligibilityChecker) {
            if (!$promotionCouponEligibilityChecker->isEligible($promotionSubject, $promotionCoupon)) {
                return false;
            }
        }

        return true;
    }
}
