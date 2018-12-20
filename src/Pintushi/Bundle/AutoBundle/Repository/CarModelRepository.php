<?php

namespace Pintushi\Bundle\AutoBundle\Repository;

use Doctrine\ORM\Query\ResultSetMapping;
use Pintushi\Bundle\AutoBundle\Entity\CarBrand;
use Pintushi\Bundle\AutoBundle\Entity\CarModel;
use Pintushi\Bundle\AutoBundle\Entity\CarSeries;
use Pintushi\Bundle\CustomerBundle\Entity\CustomerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\CoreBundle\Doctrine\ORM\EntityRepository;

/**
 * Car repository.
 */
class CarModelRepository extends EntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarModel::class);
    }

    /**
     * @param $name
     *
     * @return array
     */
    public function findByFullName($name): array
    {
        if (empty($name)) {
            return [];
        }

        $sql = "select CONCAT_WS(' ',C.name,B.name,A.name) as full_name, A.id  from pintushi_auto_model A inner join pintushi_auto_series B on  A.series_id= B.id inner join pintushi_auto_brand C on B.brand_id = C.id having full_name like ? limit 50";

        $rsm = new ResultSetMapping();
        $rsm->addEntityResult(CarModel::class, 'model');
        $rsm->addJoinedEntityResult(CarSeries::class, 'series', 'model', 'carSeries');
        $rsm->addJoinedEntityResult(CarBrand::class, 'brand', 'series', 'carBrand');
        $rsm->addFieldResult('model', 'id', 'id');
        $rsm->addFieldResult('model', 'full_name', 'name');

        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, '%'.$name.'%');

        return $query->getResult();
    }

    /**
     * @param $brandId
     * @return array
     */
    public function findCarModelBySeries($seriesId): array
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->andWhere('o.carSeries = :seriesId')
            ->setParameter('seriesId', $seriesId)
        ;
        return $queryBuilder->getQuery()->getResult();
    }

    public function getCustomerAutoCount(CustomerInterface $customer)
    {
        $qb = $this->createQueryBuilder('o');

        return $qb
            ->select('COUNT(o.id)')
            ->innerJoin('o.customers', 'c')
            ->andWhere('c.id=:customerId')
            ->setParameter('customerId', $customer->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function addCustomerQuery($queryBuilder, CustomerInterface $customer)
    {
        return $queryBuilder
            ->innerJoin('o.customers', 'c')
            ->andWhere('c.id = :customerId')
            ->setParameter('customerId', $customer->getId())
        ;
    }
}
