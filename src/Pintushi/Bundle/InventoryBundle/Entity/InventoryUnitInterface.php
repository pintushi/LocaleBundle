<?php

declare(strict_types=1);

namespace Pintushi\Bundle\InventoryBundle\Entity;

interface InventoryUnitInterface
{
    /**
     * @return StockableInterface|null
     */
    public function getStockable(): ?StockableInterface;
}
