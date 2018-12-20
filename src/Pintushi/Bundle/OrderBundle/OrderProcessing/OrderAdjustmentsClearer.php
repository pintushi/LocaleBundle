<?php

namespace Pintushi\Bundle\OrderBundle\OrderProcessing;

use Pintushi\Bundle\OrderBundle\Entity\AdjustmentInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Component\Order\Processor\OrderProcessorInterface;

final class OrderAdjustmentsClearer implements OrderProcessorInterface
{
    /**
     * @var array
     */
    private static $adjustmentsToRemove = [
        AdjustmentInterface::ORDER_ITEM_PROMOTION_ADJUSTMENT,
        AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT,
        AdjustmentInterface::ORDER_SHIPPING_PROMOTION_ADJUSTMENT,
    ];

    /**
     * {@inheritdoc}
     */
    public function process(OrderInterface $order): void
    {
        foreach (self::$adjustmentsToRemove as $type) {
            $order->removeAdjustmentsRecursively($type);
        }
    }
}
