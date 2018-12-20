<?php

namespace Pintushi\Bundle\ShippingBundle\Entity;

use Pintushi\Bundle\UserBundle\Entity\TimestampableInterface;
use Pintushi\Bundle\UserBundle\Entity\ToggleableInterface;

interface ShippingMethodInterface extends
    TimestampableInterface,
    ToggleableInterface
{
    public const TYPE_SHOP='shop';
    public const TYPE_HOME='home';

    public function getPosition(): ?int;

    public function setPosition(?int $position);

    public function getAmount(): int;

    /**
     * @param mixed $amount
     */
    public function setAmount(?int $amount);
}
