<?php


declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Entity;

interface CountablePromotionSubjectInterface extends PromotionSubjectInterface
{
    /**
     * @return int
     */
    public function getPromotionSubjectCount(): int;
}
