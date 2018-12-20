<?php

namespace Pintushi\Component\Core\Factory;

use Pintushi\Bundle\PromotionBundle\Checker\Rule\ContainsProductRuleChecker;
use Pintushi\Bundle\PromotionBundle\Checker\Rule\NthOrderRuleChecker;
use Pintushi\Bundle\PromotionBundle\Checker\Rule\HasTaxonRuleChecker;
use Pintushi\Bundle\PromotionBundle\Checker\Rule\TotalOfItemsFromTaxonRuleChecker;
use Pintushi\Component\Promotion\Checker\Rule\CartQuantityRuleChecker;
use Pintushi\Component\Promotion\Checker\Rule\ItemTotalRuleChecker;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionRuleInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionRule;

class PromotionRuleFactory implements PromotionRuleFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createNew()
    {
        return new PromotionRule();
    }

    /**
     * {@inheritdoc}
     */
    public function createItemTotal(string $channelId, int $amount): PromotionRuleInterface
    {
        /** @var PromotionRuleInterface $rule */
        $rule = $this->createNew();
        $rule->setType(ItemTotalRuleChecker::TYPE);
        $rule->setConfiguration([$channelId => ['amount' => $amount]]);

        return $rule;
    }

    /**
     * {@inheritdoc}
     */
    public function createNthOrder(int $nth): PromotionRuleInterface
    {
        /** @var PromotionRuleInterface $rule */
        $rule = $this->createNew();
        $rule->setType(NthOrderRuleChecker::TYPE);
        $rule->setConfiguration(['nth' => $nth]);

        return $rule;
    }
}
