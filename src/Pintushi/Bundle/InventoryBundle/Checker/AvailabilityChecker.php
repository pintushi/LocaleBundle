<?php

declare(strict_types=1);

namespace Pintushi\Bundle\InventoryBundle\Checker;

use Pintushi\Bundle\InventoryBundle\Entity\StockableInterface;

final class AvailabilityChecker implements AvailabilityCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function isStockAvailable(StockableInterface $stockable): bool
    {
        return $this->isStockSufficient($stockable, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function isStockSufficient(StockableInterface $stockable, int $quantity): bool
    {
        return !$stockable->isTracked() || $quantity <= ($stockable->getOnHand() - $stockable->getOnHold());
    }
}
