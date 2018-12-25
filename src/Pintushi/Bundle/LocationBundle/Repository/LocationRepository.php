<?php

namespace Pintushi\Bundle\LocationBundle\Repository;

use Videni\Bundle\RestBundle\Doctrine\ORM\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\LocationBundle\Entity\Location;

class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    public function createQueryBuilderByParent($parentCode, $level)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
                ->andWhere('o.level = :level')
                ->innerJoin('o.parent', 'p')
                ->andWhere('p.code = :parentCode')
                ->setParameter('level', $level)
                ->setParameter('parentCode', $parentCode)
            ;

        return $queryBuilder;
    }

    public function findFullPlaces(Location $location)
    {
        $qb = $this->createQueryBuilder('o');

        $qb
        ->andWhere('o.left <= :left')
        ->andWhere('o.right>= :right')
        ->setParameter('left', $location->getLeft())
        ->setParameter('right', $location->getRight())
        ->orderBy('o.level', 'ASC')
        ;

        return $qb->getQuery()->getResult();
    }
}
