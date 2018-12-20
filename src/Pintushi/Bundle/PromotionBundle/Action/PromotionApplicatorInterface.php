<?php
declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Action;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;

interface PromotionApplicatorInterface
{
    /**
     * @param PromotionSubjectInterface $subject
     * @param PromotionInterface $promotion
     */
    public function apply(PromotionSubjectInterface $subject, PromotionInterface $promotion): void;

    /**
     * @param PromotionSubjectInterface $subject
     * @param PromotionInterface $promotion
     */
    public function revert(PromotionSubjectInterface $subject, PromotionInterface $promotion): void;
}
