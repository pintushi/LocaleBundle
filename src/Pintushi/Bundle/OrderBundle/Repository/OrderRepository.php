<?php

namespace Pintushi\Bundle\OrderBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\OrderBundle\Entity\Order;
use Pintushi\Component\Order\Repository\OrderRepositoryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\CoreBundle\Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository implements OrderRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * {@inheritdoc}
     */
    public function countPlacedOrders(): int
    {
        return (int) $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->andWhere('o.state != :state')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function createCartQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->addSelect('channel')
            ->addSelect('customer')
            ->innerJoin('o.channel', 'channel')
            ->leftJoin('o.customer', 'customer')
            ->andWhere('o.state = :state')
            ->setParameter('state', OrderInterface::STATE_CART)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findLatest($count)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.state != :state')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->addOrderBy('o.checkoutCompletedAt', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByNumber(string $number): ?OrderInterface
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.state != :state')
            ->andWhere('o.number = :number')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('number', $number)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByTokenValue(string $tokenValue): ?OrderInterface
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.state != :state')
            ->andWhere('o.tokenValue = :tokenValue')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('tokenValue', $tokenValue)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findCartById($id): ?OrderInterface
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.id = :id')
            ->andWhere('o.state = :state')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findCartsNotModifiedSince(\DateTime $terminalDate)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.state = :state')
            ->andWhere('o.updatedAt < :terminalDate')
            ->setParameter('state', OrderInterface::STATE_CART)
            ->setParameter('terminalDate', $terminalDate)
            ->getQuery()
            ->getResult()
        ;
    }
}
