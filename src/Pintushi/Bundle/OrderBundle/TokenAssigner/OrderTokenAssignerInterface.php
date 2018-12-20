<?php

declare(strict_types=1);

namespace Pintushi\Bundle\OrderBundle\TokenAssigner;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;

interface OrderTokenAssignerInterface
{
    /**
     * @param OrderInterface $order
     */
    public function assignTokenValue(OrderInterface $order): void;

    /**
     * @param OrderInterface $order
     */
    public function assignTokenValueIfNotSet(OrderInterface $order): void;
}
