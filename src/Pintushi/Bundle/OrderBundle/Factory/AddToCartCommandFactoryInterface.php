<?php

namespace Pintushi\Bundle\OrderBundle\Factory;

use Pintushi\Bundle\OrderBundle\Controller\AddToCartCommandInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderItemInterface;

interface AddToCartCommandFactoryInterface
{
    public function createWithCartAndCartItem(OrderInterface $cart, OrderItemInterface $cartItem): AddToCartCommandInterface;
}
