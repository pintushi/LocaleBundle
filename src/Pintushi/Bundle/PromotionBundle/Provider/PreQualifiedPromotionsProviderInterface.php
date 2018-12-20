<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Provider;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;

interface PreQualifiedPromotionsProviderInterface
{
    /**
     * @param PromotionSubjectInterface $subject
     *
     * @return PromotionInterface[]
     */
    public function getPromotions(PromotionSubjectInterface $subject): array;
}
