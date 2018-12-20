<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Checker\Rule;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;

final class ItemTotalRuleChecker implements RuleCheckerInterface
{
    public const TYPE = 'item_total';

    /**
     * {@inheritdoc}
     */
    public function isEligible(PromotionSubjectInterface $subject, array $configuration): bool
    {
        return $subject->getPromotionSubjectTotal() >= $configuration['amount'];
    }
}
