<?php

namespace Pintushi\Bundle\LocationBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\LocationBundle\Entity\Location;

class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    public function addChildrenQuery($queryBuilder, $parent, $level)
    {
         $queryBuilder
                ->andWhere('o.level = :level')
                ->andWhere('IDENTITY(o.parent) = :parentId')
                ->setParameter('level', $level)
                ->setParameter('parentId', $parent->getId())
            ;
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
