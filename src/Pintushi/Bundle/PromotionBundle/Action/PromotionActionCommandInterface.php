<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Action;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;

interface PromotionActionCommandInterface
{
    /**
     * @param PromotionSubjectInterface $subject
     * @param array $configuration
     * @param PromotionInterface $promotion
     *
     * @return bool
     */
    public function execute(PromotionSubjectInterface $subject, array $configuration, PromotionInterface $promotion): bool;

    /**
     * @param PromotionSubjectInterface $subject
     * @param array $configuration
     * @param PromotionInterface $promotion
     */
    public function revert(PromotionSubjectInterface $subject, array $configuration, PromotionInterface $promotion): void;
}
