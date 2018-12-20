<?php

namespace Pintushi\Bundle\OrderBundle\Entity;

use Pintushi\Bundle\UserBundle\Entity\TimestampableInterface;

interface AdjustmentInterface extends TimestampableInterface
{
    const ORDER_ITEM_PROMOTION_ADJUSTMENT = 'order_item_promotion';
    const ORDER_PROMOTION_ADJUSTMENT = 'order_promotion';
    const ORDER_SHIPPING_PROMOTION_ADJUSTMENT = 'order_shipping_promotion';
    const SHIPPING_ADJUSTMENT = 'shipping';
    const SERVICE_TYPE_ADJUSTMENT = 'service_type_adjustment';

    public function getAdjustable(): ?AdjustableInterface;

    public function setAdjustable(AdjustableInterface $adjustable = null): void;

    public function getType(): string;

    public function setType(string $type): void;

    public function getLabel(): string;

    public function setLabel(string $label): void;

    public function getAmount(): int;

    public function setAmount(int $amount): void;

    public function isNeutral(): bool;

    public function setNeutral(bool $neutral): void;

    public function isLocked(): bool;

    public function lock(): void;

    public function unlock(): void;

    /**
     * Adjustments with amount < 0 are called "charges".
     *
     * @return bool
     */
    public function isCharge(): bool;

    /**
     * Adjustments with amount > 0 are called "credits".
     *
     * @return bool
     */
    public function isCredit(): bool;

    public function getOrigin(): string;

    public function setOrigin(string $originCode): void;
}
