<?php

namespace Pintushi\Bundle\ReportBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pintushi\Bundle\ReportBundle\Entity\ReportConfigGroup;
use Pintushi\Bundle\ReportBundle\Entity\Report;
use Doctrine\Common\Persistence\ManagerRegistry;

class ReportConfigGroupRepository extends ServiceEntityRepository
{
    /**
     * @var CacheProvider
     */
    private $queryResultCache;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportConfigGroup::class);
    }

    public function getAllByReport(Report $report)
    {
        return  $this
                ->createQueryBuilder('o')
                ->leftJoin('o.items', 'i')
                ->addSelect('i')
                ->andWhere('IDENTITY(o.report) = :reportId')
                ->setParameter('reportId', $report->getid())
                ->getQuery()
                ->getResult()
        ;
    }
}
