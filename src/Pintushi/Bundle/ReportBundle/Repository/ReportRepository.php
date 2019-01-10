<?php

namespace Pintushi\Bundle\ReportBundle\Repository;

use Videni\Bundle\RestBundle\Doctrine\ORM\ServiceEntityRepository;
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

    public function createCustomerQueryBuilder(CustomerInterface $customer)
    {
        $qb = $this->createQueryBuilder('o');

        $qb
            ->leftJoin('o.creator', 'c')
            ->addSelect('c')
            ->leftJoin('o.auto', 'a')
            ->addSelect('a')
            ->andWhere('IDENTITY(o.customer)=:customerId')
            ->setParameter('customerId', $customer->getId())
            ;

        return $qb;
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
