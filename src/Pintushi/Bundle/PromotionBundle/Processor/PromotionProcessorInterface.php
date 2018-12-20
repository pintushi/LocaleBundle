<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Processor;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;

interface PromotionProcessorInterface
{
    /**
     * @param PromotionSubjectInterface $subject
     */
    public function process(PromotionSubjectInterface $subject): void;
}
