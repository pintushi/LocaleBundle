<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Entity;

interface ConfigurablePromotionElementInterface
{
    /**
     * @return string|null
     */
    public function getType(): ?string;

    /**
     * @return array
     */
    public function getConfiguration(): array;

    /**
     * @return PromotionInterface|null
     */
    public function getPromotion(): ?PromotionInterface;
}
