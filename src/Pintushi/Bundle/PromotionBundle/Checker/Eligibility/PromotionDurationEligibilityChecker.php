<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Checker\Eligibility;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;

final class PromotionDurationEligibilityChecker implements PromotionEligibilityCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function isEligible(PromotionSubjectInterface $promotionSubject, PromotionInterface $promotion): bool
    {
        $now = new \DateTime();

        $startsAt = $promotion->getStartsAt();
        if (null !== $startsAt && $now < $startsAt) {
            return false;
        }

        $endsAt = $promotion->getEndsAt();
        if (null !== $endsAt && $now > $endsAt) {
            return false;
        }

        return true;
    }
}
