<?php

namespace Pintushi\Bundle\NavigationBundle\Repository;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\CacheProvider;
use Pintushi\Bundle\CoreBundle\Doctrine\ORM\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

use Pintushi\Bundle\NavigationBundle\Entity\MenuUpdateInterface;
use Pintushi\Bundle\NavigationBundle\Utils\MenuUpdateUtils;
use Pintushi\Bundle\NavigationBundle\Entity\MenuUpdate;

class MenuUpdateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MenuUpdate::class);
    }

    /**
     * @param string $menuName
     * @param Scope  $scope
     *
     * @return MenuUpdateInterface[]
     */
    public function findMenuUpdates($menuName)
    {
        $qb = $this->createQueryBuilder('u');

        return $qb->select('u')
            ->where($qb->expr()->eq('u.menu', ':menuName'))
            ->orderBy('u.id')
            ->setParameters([
                'menuName' => $menuName,
            ])
            ->getQuery()
            ->useResultCache(true)
            ->setResultCacheDriver($this->getQueryResultCache())
            ->setResultCacheId(MenuUpdateUtils::generateKey($menuName, $scope))
            ->getResult();
    }

    /**
     * @var CacheProvider
     */
    private $queryResultCache;

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
