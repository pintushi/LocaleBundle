<?php

namespace Pintushi\Bundle\OrderBundle\OrderProcessing;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Component\Order\Processor\OrderProcessorInterface;
use Pintushi\Bundle\OrderBundle\Factory\AdjustmentFactory;
use Pintushi\Bundle\OrderBundle\Entity\AdjustmentInterface;

final class ShippingChargesProcessor implements OrderProcessorInterface
{
    /**
     * @var FactoryInterface
     */
    protected $adjustmentFactory;

    /**
     * @param FactoryInterface $adjustmentFactory
     */
    public function __construct(AdjustmentFactory $adjustmentFactory)
    {
        $this->adjustmentFactory = $adjustmentFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function process(OrderInterface $order): void
    {
        // Remove all shipping adjustments, we recalculate everything from scratch.
        $order->removeAdjustments(AdjustmentInterface::SHIPPING_ADJUSTMENT);

        $shipment = $order->getShipment();
        if (!$shipment) {
            return;
        }

        $shippingMethod= $shipment->getMethod();
        try {
            $adjustment = $this->adjustmentFactory->createNew();
            $adjustment->setType(AdjustmentInterface::SHIPPING_ADJUSTMENT);
            $adjustment->setAmount($shippingMethod->getAmount());
            $adjustment->setLabel($shippingMethod->getType());

            $order->addAdjustment($adjustment);
        } catch (UndefinedShippingMethodException $exception) {
        }
    }
}
