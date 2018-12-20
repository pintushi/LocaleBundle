<?php

namespace Pintushi\Component\Order\StateResolver;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;

interface StateResolverInterface
{
    public function resolve(OrderInterface $order): void;
}
