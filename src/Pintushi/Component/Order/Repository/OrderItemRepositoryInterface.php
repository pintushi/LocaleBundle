<?php

namespace Pintushi\Component\Order\Repository;

use Pintushi\Bundle\OrderBundle\Entity\OrderItemInterface;

interface OrderItemRepositoryInterface
{
    /**
     * @param mixed $id
     * @param mixed $cartId
     *
     * @return OrderItemInterface
     */
    public function findOneByIdAndCartId($id, $cartId): ?OrderItemInterface;
}
