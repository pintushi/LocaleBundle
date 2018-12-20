<?php

namespace Pintushi\Bundle\OrderBundle\OrderProcessing;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Component\Order\Processor\OrderProcessorInterface;

final class OrderPricesRecalculator implements OrderProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(OrderInterface $order): void
    {
        foreach ($order->getItems() as $item) {
            if ($item->isImmutable()) {
                continue;
            }

            $product = $item->getProduct();
            if ($product) {
                $item->setUnitPrice($product->getPrice());
            }
        }
    }
}
