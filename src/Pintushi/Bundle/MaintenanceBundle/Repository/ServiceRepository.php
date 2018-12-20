<?php

namespace Pintushi\Bundle\MaintenanceBundle\Repository;

use Pintushi\Bundle\MaintenanceBundle\Entity\Service;
use Pintushi\Bundle\CoreBundle\Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class ServiceRepository extends EntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function findActiveServices()
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->andWhere('o.enabled=true')
            ->orderBy('o.priority', 'desc');

        return $queryBuilder->getQuery()->getResult();
    }
}
