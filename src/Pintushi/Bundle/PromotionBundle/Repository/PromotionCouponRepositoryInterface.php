<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionCouponInterface;

interface PromotionCouponRepositoryInterface
{
    /**
     * @param mixed $promotionId
     *
     * @return QueryBuilder
     */
    public function createQueryBuilderByPromotionId($promotionId): QueryBuilder;

    /**
     * @param int $codeLength
     *
     * @return int
     */
    public function countByCodeLength(int $codeLength): int;

    /**
     * @param string $code
     * @param string $promotionCode
     *
     * @return PromotionCouponInterface|null
     */
    public function findOneByCodeAndPromotionCode(string $code, string $promotionCode): ?PromotionCouponInterface;

    /**
     * @param string $promotionCode
     *
     * @return iterable
     */
    public function createPaginatorForPromotion(string $promotionCode): iterable;
}
