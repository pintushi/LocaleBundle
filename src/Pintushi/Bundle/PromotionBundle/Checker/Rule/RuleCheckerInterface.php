<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Checker\Rule;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;

interface RuleCheckerInterface
{
    /**
     * @param PromotionSubjectInterface $subject
     * @param array $configuration
     *
     * @return bool
     */
    public function isEligible(PromotionSubjectInterface $subject, array $configuration): bool;
}
