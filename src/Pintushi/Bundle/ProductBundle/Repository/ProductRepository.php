<?php

namespace Pintushi\Bundle\ProductBundle\Repository;

use Videni\Bundle\RestBundle\Doctrine\ORM\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\ProductBundle\Entity\Product;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Collections\ArrayCollection;

class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }
        /**
     * {@inheritdoc}
     */
    public function findByName($name)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findByNamePart($phrase)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.name LIKE :name')
            ->setParameter('name', '%'.$phrase.'%')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function addTrackedQuery($queryBuilder)
    {
        $queryBuilder
            ->andWhere('o.tracked = true')
        ;
    }

    public function findBySeviceAndAutoSeries(ServiceInterface $service, CarModel $carModel): ArrayCollection
    {
        $queryBuilder = $this->createServiceQueryBuilder($service->getId(), $carModel->getCarSeries()->getId());
        $result =$queryBuilder
            ->getQuery()
            ->getResult()
        ;

        return new ArrayCollection($result);
    }

    protected function createServiceQueryBuilder($serviceId, $autoSeriesId): \Doctrine\ORM\QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->innerJoin('o.autoSeries', 'series')
            ->andWhere('series.id = :seriesId')
            ->andWhere('o.enabled = true')
            ->andWhere('IDENTITY(o.service)=:serviceId')
            ->setParameter('serviceId', $serviceId)
            ->setParameter('seriesId', $autoSeriesId)
        ;

        return $queryBuilder;
    }

    public function addSeviceAndAutoQuery($queryBuilder, $serviceId, $autoSeriesId)
    {
        $queryBuilder->innerJoin('o.autoSeries', 'series')
            ->andWhere('o.enabled = true')
            ->andWhere('series.id = :seriesId')
            ->andWhere('IDENTITY(o.service)=:serviceId')
            ->setParameter('serviceId', $serviceId)
            ->setParameter('seriesId', $autoSeriesId);
    }
}
