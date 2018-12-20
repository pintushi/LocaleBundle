<?php

namespace Pintushi\Bundle\ReportBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\ReportBundle\Entity\Report;
use Pintushi\Bundle\CustomerBundle\Entity\CustomerInterface;
use Doctrine\ORM\QueryBuilder;

class ReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Report::class);
    }

    public function addCustomerQuery($queryBuilder, CustomerInterface $customer)
    {
        $queryBuilder
            ->innerJoin('o.order', 'order')
            ->leftJoin('o.creator', 'c')
            ->addSelect('c')
            ->leftJoin('o.auto', 'a')
            ->addSelect('a')
            ->andWhere('IDENTITY(order.customer)=:customerId')
            ->setParameter('customerId', $customer->getId())
            ;
    }

    /**
     * @param $id
     *
     * @return mixed | nulldev
     */
    public function findOneById($id)
    {
        return $this
            ->createReportQueryBuilder($id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $id
     * @param CustomerInterface $customer
     *
     * @return mixed |null
     */
    public function findOneByIdAndCustomer($id, CustomerInterface $customer)
    {
        return $this
            ->createReportQueryBuilder($id)
            ->innerJoin('o.order', 'order')
            ->andWhere('IDENTITY(order.customer)=:customerId')
            ->setParameter('customerId', $customer->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }

    protected function createReportQueryBuilder($id): QueryBuilder
    {
        $qb = $this->createQueryBuilder('o');

        return $qb
            ->leftJoin('o.grades', 'g', null, null, 'g.id')
            ->addSelect('g')
            ->andWhere('o.id = :id')
            ->setParameter('id', $id);
    }
}
