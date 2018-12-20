<?php

declare(strict_types=1);

namespace Pintushi\Bundle\InventoryBundle\Entity;

interface StockableInterface
{
    /**
     * @return string|null
     */
    public function getInventoryName(): ?string;

    /**
     * @return bool
     */
    public function isInStock(): bool;

    /**
     * @return int|null
     */
    public function getOnHold(): ?int;

    /**
     * @param int|null $onHold
     */
    public function setOnHold(?int $onHold): void;

    /**
     * @return int|null
     */
    public function getOnHand(): ?int;

    /**
     * @param int|null $onHand
     */
    public function setOnHand(?int $onHand): void;

    /**
     * @return bool
     */
    public function isTracked(): bool;

    /**
     * @param bool $tracked
     */
    public function setTracked(bool $tracked): void;
}
