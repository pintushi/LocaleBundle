<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Checker\Rule;

use Pintushi\Bundle\PromotionBundle\Entity\CountablePromotionSubjectInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;

final class CartQuantityRuleChecker implements RuleCheckerInterface
{
    public const TYPE = 'cart_quantity';

    /**
     * {@inheritdoc}
     */
    public function isEligible(PromotionSubjectInterface $subject, array $configuration): bool
    {
        if (!$subject instanceof CountablePromotionSubjectInterface) {
            return false;
        }

        return $subject->getPromotionSubjectCount() >= $configuration['count'];
    }
}
