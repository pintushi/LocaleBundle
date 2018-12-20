<?php

namespace Pintushi\Bundle\ShippingBundle\Entity;

use Pintushi\Bundle\UserBundle\Entity\TimestampableInterface;

interface ShipmentInterface extends TimestampableInterface, ShippingSubjectInterface
{
    const STATE_CART = 'cart';
    const STATE_READY = 'ready';
    const STATE_SHIPPED = 'shipped';
    const STATE_CANCELLED = 'cancelled';

    public function getState(): string;

    public function setState(string $state): void;

    public function getMethod(): ShippingMethodInterface;

    public function setMethod(ShippingMethodInterface $method = null): void;

    public function getTracking(): ?string;

    public function setTracking(string $tracking): void;

    public function isTracked(): bool;
}
