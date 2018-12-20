<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Entity;

interface PromotionActionInterface extends ConfigurablePromotionElementInterface
{
    /**
     * @param string|null $type
     */
    public function setType(?string $type): void;

    /**
     * @param array $configuration
     */
    public function setConfiguration(array $configuration): void;

    public function getPromotion(): ?PromotionInterface;

    /**
     * @param PromotionInterface|null $promotion
     */
    public function setPromotion(?PromotionInterface $promotion): void;
}
