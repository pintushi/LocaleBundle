<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Checker\Eligibility;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;

interface PromotionEligibilityCheckerInterface
{
    /**
     * @param PromotionSubjectInterface $promotionSubject
     * @param PromotionInterface $promotion
     *
     * @return bool
     */
    public function isEligible(PromotionSubjectInterface $promotionSubject, PromotionInterface $promotion): bool;
}
