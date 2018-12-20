<?php

namespace Pintushi\Component\Order\Modifier;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderItemInterface;
use Pintushi\Component\Order\Processor\OrderProcessorInterface;

final class OrderModifier implements OrderModifierInterface
{
    /**
     * @var OrderProcessorInterface
     */
    private $orderProcessor;

    /**
     * @var OrderItemQuantityModifierInterface
     */
    private $orderItemQuantityModifier;

    public function __construct(
        OrderProcessorInterface $orderProcessor,
        OrderItemQuantityModifierInterface $orderItemQuantityModifier
    ) {
        $this->orderProcessor = $orderProcessor;
        $this->orderItemQuantityModifier = $orderItemQuantityModifier;
    }

    public function addToOrder(OrderInterface $order, OrderItemInterface $item): void
    {
        $this->resolveOrderItem($order, $item);

        $this->orderProcessor->process($order);
    }

    public function removeFromOrder(OrderInterface $order, OrderItemInterface $item): void
    {
        $order->removeItem($item);
        $this->orderProcessor->process($order);
    }

    private function resolveOrderItem(OrderInterface $order, OrderItemInterface $item): void
    {
        foreach ($order->getItems() as $existingItem) {
            if ($item->equals($existingItem)) {
                $this->orderItemQuantityModifier->modify(
                    $existingItem,
                    $existingItem->getQuantity() + $item->getQuantity()
                );

                return;
            }
        }

        $order->addItem($item);
    }
}
