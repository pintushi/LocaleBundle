<?php

namespace Pintushi\Bundle\MaintenanceBundle\Repository;

use Pintushi\Bundle\MaintenanceBundle\Entity\ServiceGroup;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\CoreBundle\Doctrine\ORM\EntityRepository;

class ServiceGroupRepository extends EntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceGroup::class);
    }

    public function findActiveGroups()
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->andWhere('o.enabled=true')
            ->leftJoin('o.services', 's')
            ->addSelect('s')
            ->orderBy('o.position', 'asc')
            ->orderBy('s.position', 'asc')
            ;

        return $queryBuilder->getQuery()->getResult();
    }
}
