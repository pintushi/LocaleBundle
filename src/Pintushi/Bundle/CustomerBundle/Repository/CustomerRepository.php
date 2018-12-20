<?php

namespace Pintushi\Bundle\CustomerBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Pintushi\Bundle\CoreBundle\Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\CustomerBundle\Entity\Customer;

class CustomerRepository extends EntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }
}
