<?php

namespace Pintushi\Component\Order\Context;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\OrderBundle\Entity\Order;

final class CartContext implements CartContextInterface
{
    /**
     * {@inheritdoc}
     */
    public function getCart(): ?OrderInterface
    {
        return new Order();
    }
}
