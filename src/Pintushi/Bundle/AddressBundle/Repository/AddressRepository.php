<?php

namespace Pintushi\Bundle\AddressBundle\Repository;

use Pintushi\Bundle\CoreBundle\Doctrine\ORM\EntityRepository;
use Pintushi\Bundle\CustomerBundle\Entity\CustomerInterface;
use Pintushi\Bundle\AddressBundle\Entity\Address;
use Doctrine\Common\Persistence\ManagerRegistry;

class AddressRepository extends EntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }

    public function getCustomerAddressCount(CustomerInterface $customer)
    {
        $qb = $this->createQueryBuilder('o');

        return $qb->select('COUNT(o.id)')
            ->innerJoin('o.customer', 'c')
            ->where('c.id=:customerId')
            ->setParameter('customerId', $customer->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function addCustomerQuery($queryBuilder, CustomerInterface $customer)
    {
        $queryBuilder
            ->innerJoin('o.customer', 'customer')
            ->andWhere('customer = :customer')
            ->setParameter('customer', $customer)
            ;
    }
}
