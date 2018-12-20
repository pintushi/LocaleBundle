<?php


namespace Pintushi\Bundle\OrderBundle\Controller;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderItemInterface;

interface AddToCartCommandInterface
{
    public function getCart(): OrderInterface;

    public function getCartItem(): OrderItemInterface;
}
