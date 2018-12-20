<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Repository;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;

interface PromotionRepositoryInterface
{
    /**
     * @return PromotionInterface[]
     */
    public function findActive(): array;

    /**
     * @param string $name
     *
     * @return PromotionInterface[]
     */
    public function findByName(string $name): array;
}
