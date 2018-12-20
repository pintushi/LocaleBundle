<?php

namespace Pintushi\Bundle\PromotionBundle\Action;

use Pintushi\Bundle\OrderBundle\Entity\AdjustmentInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderItemUnitInterface;
use Pintushi\Bundle\PromotionBundle\Action\PromotionActionCommandInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;
use Webmozart\Assert\Assert;

abstract class DiscountPromotionActionCommand implements PromotionActionCommandInterface
{
    /**
     * @param array $configuration
     */
    abstract protected function isConfigurationValid(array $configuration): void;

    /**
     * {@inheritdoc}
     */
    public function revert(PromotionSubjectInterface $subject, array $configuration, PromotionInterface $promotion): void
    {
        if (!$this->isSubjectValid($subject)) {
            return;
        }

        foreach ($subject->getItems() as $item) {
                $this->removeOrderItemPromotionAdjustmentsByOrigin($item, $promotion);
        }
    }

    protected function isSubjectValid(PromotionSubjectInterface $subject): bool
    {
        Assert::implementsInterface($subject, OrderInterface::class);

        return 0 !== $subject->countItems();
    }

    private function removeOrderItemPromotionAdjustmentsByOrigin(
        OrderItemInterface $orderItem,
        PromotionInterface $promotion
    ): void {
        foreach ($orderItem->getAdjustments(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT) as $adjustment) {
            if ($promotion->getId() === $adjustment->getOrigin()) {
                $orderItem->removeAdjustment($adjustment);
            }
        }
    }
}
