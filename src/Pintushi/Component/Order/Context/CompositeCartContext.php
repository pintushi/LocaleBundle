<?php

namespace Pintushi\Component\Order\Context;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Zend\Stdlib\PriorityQueue;

final class CompositeCartContext implements CartContextInterface
{
    /**
     * @var PriorityQueue|CartContextInterface[]
     */
    private $cartContexts;

    public function __construct()
    {
        $this->cartContexts = new PriorityQueue();
    }

    public function addContext(CartContextInterface $cartContext, int $priority = 0): void
    {
        $this->cartContexts->insert($cartContext, $priority);
    }

    /**
     * {@inheritdoc}
     */
    public function getCart(): ?OrderInterface
    {
        foreach ($this->cartContexts as $cartContext) {
            try {
                return $cartContext->getCart();
            } catch (CartNotFoundException $exception) {
                continue;
            }
        }

        throw new CartNotFoundException();
    }
}
