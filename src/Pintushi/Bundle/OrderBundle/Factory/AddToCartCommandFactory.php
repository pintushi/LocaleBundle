<?php


namespace Pintushi\Bundle\OrderBundle\Factory;

use Pintushi\Bundle\OrderBundle\Controller\AddToCartCommand;
use Pintushi\Bundle\OrderBundle\Controller\AddToCartCommandInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderItemInterface;

final class AddToCartCommandFactory implements AddToCartCommandFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createWithCartAndCartItem(OrderInterface $cart, OrderItemInterface $cartItem): AddToCartCommandInterface
    {
        return new AddToCartCommand($cart, $cartItem);
    }
}
