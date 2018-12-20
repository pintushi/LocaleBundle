<?php

namespace Pintushi\Bundle\PromotionBundle\Applicator;

use Pintushi\Bundle\PromotionBundle\Distributor\IntegerDistributorInterface;
use Pintushi\Bundle\OrderBundle\Entity\AdjustmentInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderItemInterface;
use Pintushi\Bundle\OrderBundle\Factory\AdjustmentFactoryInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;
use Webmozart\Assert\Assert;

final class OrderItemsPromotionAdjustmentsApplicator implements OrderItemsPromotionAdjustmentsApplicatorInterface
{
    /**
     * @var AdjustmentFactoryInterface
     */
    private $adjustmentFactory;

    /**
     * @var IntegerDistributorInterface
     */
    private $distributor;

    public function __construct(
        AdjustmentFactoryInterface $adjustmentFactory
    ) {
        $this->adjustmentFactory = $adjustmentFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(OrderInterface $order, PromotionInterface $promotion, array $adjustmentsAmounts): void
    {
        Assert::eq($order->countItems(), count($adjustmentsAmounts));

        $i = 0;
        foreach ($order->getItems() as $item) {
            $adjustmentAmount = $adjustmentsAmounts[$i++];
            if (0 === $adjustmentAmount) {
                continue;
            }

             $this->addAdjustment($promotion, $item, $promotionAmount);
        }
    }

    private function addAdjustment(PromotionInterface $promotion, OrderItemInterface $item, int $amount): void
    {
        $adjustment = $this->adjustmentFactory
            ->createWithData(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT, $promotion->getName(), $amount)
        ;
        $adjustment->setOrigin($promotion->getId());

        $item->addAdjustment($adjustment);
    }
}
