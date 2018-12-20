<?php

declare(strict_types=1);

namespace Pintushi\Bundle\InventoryBundle\Checker;

use Pintushi\Bundle\InventoryBundle\Entity\StockableInterface;


interface AvailabilityCheckerInterface
{
    /**
     * @param StockableInterface $stockable
     *
     * @return bool
     */
    public function isStockAvailable(StockableInterface $stockable): bool;

    /**
     * @param StockableInterface $stockable
     * @param int $quantity
     *
     * @return bool
     */
    public function isStockSufficient(StockableInterface $stockable, int $quantity): bool;
}
