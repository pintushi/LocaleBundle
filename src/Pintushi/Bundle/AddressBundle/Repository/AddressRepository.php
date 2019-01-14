<?php

namespace Pintushi\Bundle\AddressBundle\Repository;

use Videni\Bundle\RestBundle\Doctrine\ORM\ServiceEntityRepository;
use Pintushi\Bundle\CustomerBundle\Entity\CustomerInterface;
use Pintushi\Bundle\AddressBundle\Entity\Address;
use Doctrine\Common\Persistence\ManagerRegistry;

class AddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }

    public function getCustomerAddressesCount(CustomerInterface $customer)
    {
        $qb = $this->createCustomerQueryBuilder($customer);

        return
            $qb->select('count(o.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function createCustomerQueryBuilder(CustomerInterface $customer)
    {
        $qb = $this->createQueryBuilder('o');

        $qb
           ->andWhere('IDENTITY(o.customer)=:customerId')
           ->setParameter('customerId', $customer->getId())
        ;

        return $qb;
    }
}
