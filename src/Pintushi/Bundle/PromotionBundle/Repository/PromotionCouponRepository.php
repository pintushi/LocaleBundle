<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionCouponInterface;
use Pintushi\Bundle\PromotionBundle\Repository\PromotionCouponRepositoryInterface;
use Pintushi\Bundle\CoreBundle\Doctrine\ORM\ServiceEntityRepository;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionCoupon;
use Doctrine\Common\Persistence\ManagerRegistry;

class PromotionCouponRepository extends ServiceEntityRepository implements PromotionCouponRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PromotionCoupon::class);
    }

    /**
     * {@inheritdoc}
     */
    public function createQueryBuilderByPromotionId($promotionId): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.promotion = :promotionId')
            ->setParameter('promotionId', $promotionId)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function countByCodeLength(int $codeLength): int
    {
        return (int) $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->andWhere('LENGTH(o.code) = :codeLength')
            ->setParameter('codeLength', $codeLength)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByCodeAndPromotionCode(string $code, string $promotionId): ?PromotionCouponInterface
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.promotion', 'promotion')
            ->where('promotion.code = :promotionCode')
            ->andWhere('o.id = :promotionId')
            ->setParameter('promotionId', $promotionId)
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function createPaginatorForPromotion(string $promotionCode): iterable
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->leftJoin('o.promotion', 'promotion')
            ->where('promotion.code = :promotionCode')
            ->setParameter('promotionCode', $promotionCode)
        ;

        return $this->getPaginator($queryBuilder);
    }
}
