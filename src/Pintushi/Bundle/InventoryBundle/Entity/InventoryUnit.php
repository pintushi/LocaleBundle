<?php

declare(strict_types=1);

namespace Pintushi\Bundle\InventoryBundle\Entity;

class InventoryUnit implements InventoryUnitInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var StockableInterface
     */
    protected $stockable;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getStockable(): ?StockableInterface
    {
        return $this->stockable;
    }

    /**
     * @param StockableInterface $stockable
     */
    public function setStockable(StockableInterface $stockable): void
    {
        $this->stockable = $stockable;
    }
}
