<?php

namespace Pintushi\Component\Order\Context;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;

interface CartContextInterface
{
    public function getCart(): ?OrderInterface;
}
