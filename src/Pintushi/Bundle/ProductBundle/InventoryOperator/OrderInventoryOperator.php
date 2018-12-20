<?php

declare(strict_types=1);

namespace Pintushi\Bundle\ProductBundle\InventoryOperator;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Pintushi\Bundle\InventoryBundle\Operator\OrderInventoryOperatorInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderItemInterface;

final class OrderInventoryOperator implements OrderInventoryOperatorInterface
{
    /**
     * @var OrderInventoryOperatorInterface
     */
    private $decoratedOperator;

    /**
     * @var EntityManagerInterface
     */
    private $productEntityManager;

    /**
     * @param OrderInventoryOperatorInterface $decoratedOperator
     * @param EntityManagerInterface $productEntityManager
     */
    public function __construct(
        OrderInventoryOperatorInterface $decoratedOperator,
        EntityManagerInterface $productEntityManager
    ) {
        $this->decoratedOperator = $decoratedOperator;
        $this->productEntityManager = $productEntityManager;
    }

    /**
     * {@inheritdoc}
     *
     * @throws OptimisticLockException
     */
    public function cancel(OrderInterface $order): void
    {
        $this->lockProducts($order);

        $this->decoratedOperator->cancel($order);
    }

    /**
     * {@inheritdoc}
     *
     * @throws OptimisticLockException
     */
    public function hold(OrderInterface $order): void
    {
        $this->lockProducts($order);

        $this->decoratedOperator->hold($order);
    }

    /**
     * {@inheritdoc}
     *
     * @throws OptimisticLockException
     */
    public function sell(OrderInterface $order): void
    {
        $this->lockProducts($order);

        $this->decoratedOperator->sell($order);
    }

    /**
     * @param OrderInterface $order
     *
     * @throws OptimisticLockException
     */
    private function lockProducts(OrderInterface $order): void
    {
        /** @var OrderItemInterface $orderItem */
        foreach ($order->getItems() as $orderItem) {
            $product = $orderItem->getProduct();

            if (!$product->isTracked()) {
                continue;
            }

            $this->productEntityManager->lock($product, LockMode::OPTIMISTIC, $product->getVersion());
        }
    }
}
