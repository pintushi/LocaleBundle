<?php

namespace Pintushi\Bundle\CustomerBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Videni\Bundle\RestBundle\Doctrine\ORM\ServiceEntityRepository as EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\CustomerBundle\Entity\Customer;

class CustomerRepository extends EntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }
}
