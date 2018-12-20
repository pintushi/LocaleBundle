<?php

namespace Pintushi\Component\Order\Repository;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;

interface OrderRepositoryInterface
{
    public function countPlacedOrders(): int;

    /**
     * @param int $count
     *
     * @return OrderInterface[]
     */
    public function findLatest($count);

    public function findOneByNumber(string $number): ?OrderInterface;

    /**
     * @param mixed $id
     *
     * @return OrderInterface|null
     */
    public function findCartById($id): ?OrderInterface;

    /**
     * @param \DateTime $terminalDate
     *
     * @return OrderInterface[]
     */
    public function findCartsNotModifiedSince(\DateTime $terminalDate);

    public function createCartQueryBuilder(): \Doctrine\ORM\QueryBuilder;
}
