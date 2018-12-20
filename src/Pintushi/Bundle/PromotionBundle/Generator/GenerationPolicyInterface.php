<?php


declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Generator;

interface GenerationPolicyInterface
{
    /**
     * @param PromotionCouponGeneratorInstructionInterface $instruction
     *
     * @return bool
     */
    public function isGenerationPossible(PromotionCouponGeneratorInstructionInterface $instruction): bool;

    /**
     * @param PromotionCouponGeneratorInstructionInterface $instruction
     *
     * @return int
     */
    public function getPossibleGenerationAmount(PromotionCouponGeneratorInstructionInterface $instruction): int;
}
