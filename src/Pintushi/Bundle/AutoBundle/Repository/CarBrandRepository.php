<?php

namespace Pintushi\Bundle\AutoBundle\Repository;

use Videni\Bundle\RestBundle\Doctrine\ORM\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\AutoBundle\Entity\CarBrand;

class CarBrandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarBrand::class);
    }

    /**
     * find specific brand associated with its series and models.
     *
     * @return array
     */
    public function findCarBrandWithSeriesAndModel($id): array
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.carSeries', 'cs')
            ->leftJoin('cs.carModels', 'm')
            ->andWhere('o.id=:brandId')
            ->setParameter('brandId', $id)
            ->addSelect('cs', 'm')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findHotBrands($maxResult = 6)
    {
        $queryBuilder =  $this->_em->getConnection()->createQueryBuilder();

        $result= $queryBuilder->select('D.name', 'D.id', 'count(B.customer_id) as frequency ')
            ->from('pintushi_auto_model', 'A')
            ->innerJoin('A', 'pintushi_customer__car_model', 'B', 'A.id=B.car_model_id')
            ->innerJoin('A', 'pintushi_auto_series', 'C', 'A.series_id=C.id')
            ->innerJoin('C', 'pintushi_auto_brand', 'D', 'C.brand_id=D.id')
            ->groupBy('A.id')
            ->orderBy('frequency', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults($maxResult)
            ->execute()
            ->fetchAll();

        return $result;
    }
}
