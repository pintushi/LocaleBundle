<?php

namespace Pintushi\Bundle\AutoBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Videni\Bundle\RestBundle\Doctrine\ORM\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\AutoBundle\Entity\CarSeries;

/**
 * Car repository.
 */
class CarSeriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarSeries::class);
    }

    public function createQueryBuilderWithBrand($brandId): QueryBuilder
    {
        return  $this->createQueryBuilder('o')
            ->where('o.brand = :brandId')
            ->setParameter('brandId', $brandId);
    }

    /**
     * @param $brandId
     *
     * @return array
     */
    public function getAllByBrandId($id, $enabled = true): array
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->leftJoin('o.carModels', 'm')
            ->addSelect('m')
            ->andWhere('o.brand = :brandId')
            ->andWhere('o.enabled = :enabled')
            ->setParameter('brandId', $id)
            ->setParameter('enabled', $enabled)
        ;

        return $queryBuilder
                ->getQuery()
                ->getResult();
    }
}
