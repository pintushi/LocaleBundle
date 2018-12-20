<?php

declare(strict_types = 1);

namespace Pintushi\Bundle\OrderBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Pintushi\Bundle\UserBundle\Entity\TimestampableInterface;

interface OrderInterface extends AdjustableInterface, TimestampableInterface
{
    const STATE_CART = 'cart';
    const STATE_NEW = 'new';
    const STATE_CANCELLED = 'cancelled';
    const STATE_FULFILLED = 'fulfilled';

    public function getNumber(): ?string;

    public function setNumber(string $number): void;

    public function getNotes(): ?string;

    public function setNotes(string $notes): void;

    /**
     * @return Collection|OrderItemInterface[]
     */
    public function getItems(): Collection;

    public function clearItems(): void;

    public function countItems(): int;

    public function addItem(OrderItemInterface $item): void;

    public function removeItem(OrderItemInterface $item): void;

    public function hasItem(OrderItemInterface $item): bool;

    public function getItemsTotal(): int;

    public function recalculateItemsTotal(): void;

    public function getTotal(): int;

    public function getTotalQuantity(): int;

    public function getState(): string;

    public function setState(string $state): void;

    public function isEmpty(): bool;

    /**
     *
     * @return array
     */
    public function getAdjustmentsRecursively(?string $type): array;

    public function getAdjustmentsTotalRecursively(?string $type): int;

    public function removeAdjustmentsRecursively(?string $type): void;
}
