<?php

namespace Pintushi\Bundle\OrderBundle\Repository;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderItem;
use Pintushi\Component\Order\Repository\OrderItemRepositoryInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderItemInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\CoreBundle\Doctrine\ORM\EntityRepository;

class OrderItemRepository extends EntityRepository implements OrderItemRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderItem::class);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByIdAndCartId($id, $cartId): ?OrderItemInterface
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.order', 'cart')
            ->andWhere('cart.state = :state')
            ->andWhere('o.id = :id')
            ->andWhere('cart.id = :cartId')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('id', $id)
            ->setParameter('cartId', $cartId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
