<?php

namespace Pintushi\Bundle\TaxonomyBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Videni\Bundle\RestBundle\Doctrine\ORM\ServiceEntityRepository;
use Pintushi\Bundle\TaxonomyBundle\Entity\TaxonInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pintushi\Bundle\TaxonomyBundle\Entity\Taxon;

class TaxonRepository extends ServiceEntityRepository implements TaxonRepositoryInterface
{
    /**
     * @var AclHelper
     */
    private $aclHelper;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Taxon::class);
    }

    /**
     * @param AclHelper $aclHelper
     */
    public function setAclHelper(AclHelper $aclHelper)
    {
        $this->aclHelper = $aclHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function findChildren(string $parentCode, ?string $locale = null)
    {
        return $this->createQueryBuilder('o')
            ->addSelect('child')
            ->innerJoin('o.parent', 'parent')
            ->leftJoin('o.children', 'child')
            ->andWhere('parent.code = :parentCode')
            ->addOrderBy('o.position')
            ->setParameter('parentCode', $parentCode)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findRootNodes()
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.parent IS NULL')
            ->addOrderBy('o.position')
            ->getQuery()
            ->getResult()
        ;
    }
}
