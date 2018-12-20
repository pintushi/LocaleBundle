<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Checker\Eligibility;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;

final class PromotionUsageLimitEligibilityChecker implements PromotionEligibilityCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function isEligible(PromotionSubjectInterface $promotionSubject, PromotionInterface $promotion): bool
    {
        if (null === $usageLimit = $promotion->getUsageLimit()) {
            return true;
        }

        if ($promotion->getUsed() < $usageLimit) {
            return true;
        }

        return false;
    }
}
