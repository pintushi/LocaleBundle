<?php

namespace Pintushi\Bundle\CustomerBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Pintushi\Bundle\CoreBundle\Doctrine\ORM\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\CustomerBundle\Entity\CustomerGroup;

class CustomerGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerGroup::class);
    }
}
