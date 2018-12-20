<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Generator;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionCouponInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;

interface PromotionCouponGeneratorInterface
{
    /**
     * @param PromotionInterface $promotion
     * @param PromotionCouponGeneratorInstructionInterface $instruction
     *
     * @return array|PromotionCouponInterface[]
     */
    public function generate(PromotionInterface $promotion, PromotionCouponGeneratorInstructionInterface $instruction): array;
}
