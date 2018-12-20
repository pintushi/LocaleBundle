<?php

namespace Pintushi\Bundle\ReportBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\ReportBundle\Entity\ReportConfigItem;
use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationInterface;
use Doctrine\Common\Cache\CacheProvider;
use Doctrine\Common\Cache\ArrayCache;

class ReportConfigItemRepository extends ServiceEntityRepository
{
    const CACHE_KEY_PREFIX = 'report_config';

    private $queryResultCache;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportConfigItem::class);
    }

    public function getReportConfig(OrganizationInterface $organization)
    {
        return $this
                ->createReportConfigQueryBuilder($organization)
                ->getQuery()
                ->useResultCache(true)
                ->setResultCacheDriver($this->getQueryResultCache())
                ->setResultCacheLifetime(3600)
                ->setResultCacheId(sprintf('%s_%s', self::CACHE_KEY_PREFIX, $organization->getId()))
                ->getResult()
        ;
    }

    public function createReportConfigQueryBuilder(OrganizationInterface $organization)
    {
        return $this->createQueryBuilder('o', 'o.id')
            ->leftJoin('o.group', 'g')
            ->addSelect('g')
            ->andWhere('IDENTITY(g.organization) = :organizationId')
            ->setParameter('organizationId', $organization->getId())
            ->andWhere('IDENTITY(o.report) is null')
            ->addOrderBy('o.priority', 'ASC')
            ;
    }

    /**
     * @param CacheProvider $queryResultCache
     */
    public function setQueryResultCache(CacheProvider $queryResultCache)
    {
        $this->queryResultCache = $queryResultCache;
    }

    /**
     * @return CacheProvider
     */
    private function getQueryResultCache()
    {
        if (!$this->queryResultCache) {
            $this->queryResultCache = new ArrayCache();
        }

        return $this->queryResultCache;
    }
}
