<?php

namespace Pintushi\Bundle\InventoryBundle\Operator;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderItemInterface;
use Pintushi\Bundle\OrderBundle\OrderPaymentStates;
use Webmozart\Assert\Assert;

final class OrderInventoryOperator implements OrderInventoryOperatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function cancel(OrderInterface $order): void
    {
        if (in_array(
            $order->getPaymentState(),
            [OrderPaymentStates::STATE_PAID, OrderPaymentStates::STATE_REFUNDED],
            true
        )) {
            $this->giveBack($order);

            return;
        }

        $this->release($order);
    }

    /**
     * {@inheritdoc}
     */
    public function hold(OrderInterface $order): void
    {
        /** @var OrderItemInterface $orderItem */
        foreach ($order->getItems() as $orderItem) {
            $product = $orderItem->getProduct();

            if (!$product || !$product->isTracked()) {
                continue;
            }

            $product->setOnHold($product->getOnHold() + $orderItem->getQuantity());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sell(OrderInterface $order): void
    {
        /** @var OrderItemInterface $orderItem */
        foreach ($order->getItems() as $orderItem) {
            $product = $orderItem->getProduct();

            if (!$product || !$product->isTracked()) {
                continue;
            }

            Assert::greaterThanEq(
                ($product->getOnHold() - $orderItem->getQuantity()),
                0,
                sprintf(
                    'Not enough units to decrease on hold quantity from the inventory of a variant "%s".',
                    $product->getName()
                )
            );

            Assert::greaterThanEq(
                ($product->getOnHand() - $orderItem->getQuantity()),
                0,
                sprintf(
                    'Not enough units to decrease on hand quantity from the inventory of a variant "%s".',
                    $product->getName()
                )
            );

            $product->setOnHold($product->getOnHold() - $orderItem->getQuantity());
            $product->setOnHand($product->getOnHand() - $orderItem->getQuantity());
        }
    }

    private function release(OrderInterface $order): void
    {
        /** @var OrderItemInterface $orderItem */
        foreach ($order->getItems() as $orderItem) {
            $product = $orderItem->getProduct();

            if (!$product || !$product->isTracked()) {
                continue;
            }

            Assert::greaterThanEq(
                ($product->getOnHold() - $orderItem->getQuantity()),
                0,
                sprintf(
                    'Not enough units to decrease on hold quantity from the inventory of a variant "%s".',
                    $product->getName()
                )
            );

            $product->setOnHold($product->getOnHold() - $orderItem->getQuantity());
        }
    }

    private function giveBack(OrderInterface $order): void
    {
        /** @var OrderItemInterface $orderItem */
        foreach ($order->getItems() as $orderItem) {
            $product = $orderItem->getProduct();

            if (!$product || !$product->isTracked()) {
                continue;
            }

            $product->setOnHand($product->getOnHand() + $orderItem->getQuantity());
        }
    }
}
