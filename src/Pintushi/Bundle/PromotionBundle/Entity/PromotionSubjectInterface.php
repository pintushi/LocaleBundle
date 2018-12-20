<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Entity;

use Doctrine\Common\Collections\Collection;

interface PromotionSubjectInterface
{
    /**
     * @return int
     */
    public function getPromotionSubjectTotal(): int;

    /**
     * @return Collection|PromotionInterface[]
     */
    public function getPromotions(): Collection;

    /**
     * @param PromotionInterface $promotion
     *
     * @return bool
     */
    public function hasPromotion(PromotionInterface $promotion): bool;

    /**
     * @param PromotionInterface $promotion
     */
    public function addPromotion(PromotionInterface $promotion): void;

    /**
     * @param PromotionInterface $promotion
     */
    public function removePromotion(PromotionInterface $promotion): void;
}
