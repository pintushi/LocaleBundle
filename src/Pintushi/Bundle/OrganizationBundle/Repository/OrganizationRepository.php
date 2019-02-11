<?php

namespace Pintushi\Bundle\OrganizationBundle\Repository;

use Pintushi\Bundle\OrganizationBundle\Entity\Organization;
use Videni\Bundle\RestBundle\Doctrine\ORM\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class OrganizationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Organization::class);
    }

    public function getGlobalOrganization()
    {
        $qb = $this
            ->createQueryBuilder('o')
            ->andWhere('o.global = true')
        ;

        return $qb
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
