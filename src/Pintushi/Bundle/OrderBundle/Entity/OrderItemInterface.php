<?php

namespace Pintushi\Bundle\OrderBundle\Entity;

interface OrderItemInterface extends AdjustableInterface, OrderAwareInterface
{
    public function getUnitPrice(): int;

    public function setUnitPrice($unitPrice): void;

    public function getTotal(): int;

    /**
     * Checks whether the item given as argument corresponds to
     * the same cart item. Can be overwritten to enable merge quantities.
     *
     * @param OrderItemInterface $orderItem
     *
     * @return bool
     */
    public function equals(OrderItemInterface $orderItem): bool;

    public function isImmutable(): bool;

    public function setImmutable(bool $immutable): void;

    public function recalculateUnitsTotal(): void;

    public function getQuantity();

    public function setQuantity($quantity);
}
