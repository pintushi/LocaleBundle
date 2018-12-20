<?php

namespace Pintushi\Bundle\PromotionBundle\Action;

use Pintushi\Bundle\OrderBundle\Entity\AdjustmentInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderItemInterface;
use Pintushi\Bundle\PromotionBundle\Action\PromotionActionCommandInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;
use Pintushi\Bundle\OrderBundle\Factory\AdjustmentFactory;

final class ShippingPercentageDiscountPromotionActionCommand implements PromotionActionCommandInterface
{
    const TYPE = 'shipping_percentage_discount';

    /**
     * @var FactoryInterface
     */
    protected $adjustmentFactory;

    public function __construct(AdjustmentFactory $adjustmentFactory)
    {
        $this->adjustmentFactory = $adjustmentFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(PromotionSubjectInterface $subject, array $configuration, PromotionInterface $promotion): bool
    {
        if (!$subject instanceof OrderInterface) {
            throw new UnexpectedTypeException($subject, OrderInterface::class);
        }

        if (!isset($configuration['percentage'])) {
            return false;
        }

        $adjustment = $this->createAdjustment($promotion);

        $adjustmentAmount = (int) round($subject->getAdjustmentsTotal(AdjustmentInterface::SHIPPING_ADJUSTMENT) * $configuration['percentage']);
        if (0 === $adjustmentAmount) {
            return false;
        }

        $adjustment->setAmount(-$adjustmentAmount);
        $subject->addAdjustment($adjustment);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function revert(PromotionSubjectInterface $subject, array $configuration, PromotionInterface $promotion): void
    {
        if (!$subject instanceof OrderInterface && !$subject instanceof OrderItemInterface) {
            throw new \RuntimeException(
                $subject,
                'Pintushi\Bundle\OrderBundle\Entity\OrderInterface or Pintushi\Bundle\OrderBundle\Entity\OrderItemInterface'
            );
        }

        foreach ($subject->getAdjustments(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT) as $adjustment) {
            if ($promotion->getId() === $adjustment->getOrigin()) {
                $subject->removeAdjustment($adjustment);
            }
        }
    }

    protected function createAdjustment(
        PromotionInterface $promotion,
        string $type = AdjustmentInterface::ORDER_SHIPPING_PROMOTION_ADJUSTMENT
    ): AdjustmentInterface {
        /** @var AdjustmentInterface $adjustment */
        $adjustment = $this->adjustmentFactory->createNew();
        $adjustment->setType($type);
        $adjustment->setLabel($promotion->getName());
        $adjustment->setOrigin($promotion->getId());

        return $adjustment;
    }
}
