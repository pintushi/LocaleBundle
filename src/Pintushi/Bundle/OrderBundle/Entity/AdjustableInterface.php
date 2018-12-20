<?php

namespace Pintushi\Bundle\OrderBundle\Entity;

use Doctrine\Common\Collections\Collection;

interface AdjustableInterface
{
    /**
     * @param null|string $type
     *
     * @return Collection|AdjustmentInterface[]
     */
    public function getAdjustments(?string $type = null);

    public function addAdjustment(AdjustmentInterface $adjustment): void;

    public function removeAdjustment(AdjustmentInterface $adjustment): void;

    public function getAdjustmentsTotal(?string $type = null): int;

    public function removeAdjustments(string $type): void;

    /**
     * Recalculates adjustments total. Should be used after adjustment change.
     */
    public function recalculateAdjustmentsTotal(): void;
}
