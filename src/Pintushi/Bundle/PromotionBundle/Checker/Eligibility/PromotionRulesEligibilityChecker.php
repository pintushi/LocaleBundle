<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Checker\Eligibility;

use Pintushi\Bundle\PromotionBundle\Checker\Rule\RuleCheckerInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionRuleInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

final class PromotionRulesEligibilityChecker implements PromotionEligibilityCheckerInterface
{
    /**
     * @var ServiceRegistryInterface
     */
    private $ruleRegistry;

    /**
     * @param ServiceRegistryInterface $ruleRegistry
     */
    public function __construct(ServiceRegistryInterface $ruleRegistry)
    {
        $this->ruleRegistry = $ruleRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function isEligible(PromotionSubjectInterface $promotionSubject, PromotionInterface $promotion): bool
    {
        if (!$promotion->hasRules()) {
            return true;
        }

        foreach ($promotion->getRules() as $rule) {
            if (!$this->isEligibleToRule($promotionSubject, $rule)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param PromotionSubjectInterface $subject
     * @param PromotionRuleInterface $rule
     *
     * @return bool
     */
    private function isEligibleToRule(PromotionSubjectInterface $subject, PromotionRuleInterface $rule): bool
    {
        /** @var RuleCheckerInterface $checker */
        $checker = $this->ruleRegistry->get($rule->getType());

        return $checker->isEligible($subject, $rule->getConfiguration());
    }
}
