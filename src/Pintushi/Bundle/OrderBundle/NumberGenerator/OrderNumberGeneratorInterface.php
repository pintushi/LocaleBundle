<?php

namespace Pintushi\Bundle\OrderBundle\NumberGenerator;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;

interface OrderNumberGeneratorInterface
{
    public function generate(OrderInterface $order): string;
}
