<?php


namespace Pintushi\Bundle\OrderBundle\Controller;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderItemInterface;

final class AddToCartCommand implements AddToCartCommandInterface
{
    /**
     * @var OrderInterface
     */
    private $cart;

    /**
     * @var OrderItemInterface
     */
    private $cartItem;

    public function __construct(OrderInterface $cart, OrderItemInterface $cartItem)
    {
        $this->cart = $cart;
        $this->cartItem = $cartItem;
    }

    public function getCart(): OrderInterface
    {
        return $this->cart;
    }

    public function getCartItem(): OrderItemInterface
    {
        return $this->cartItem;
    }
}
