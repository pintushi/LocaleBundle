<?php

namespace Pintushi\Bundle\OrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Webmozart\Assert\Assert;
use Pintushi\Bundle\ProductBundle\Entity\ProductInterface;
use Pintushi\Bundle\MaintenanceBundle\Entity\ServiceInterface;
use Pintushi\Bundle\InventoryBundle\Entity\StockableInterface;

class OrderItem implements OrderItemInterface
{
     /**
     * @var mixed
     */
    protected $id;

    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @var int
     */
    protected $quantity = 0;

    /**
     * @var int
     */
    protected $unitPrice = 0;

    /**
     * @var int
     */
    protected $total = 0;

    /**
     * @var bool
     */
    protected $immutable = false;

    /**
     * @var int
     */
    protected $unitsTotal = 0;

    /**
     * @var Collection|AdjustmentInterface[]
     */
    protected $adjustments;

   /**
     * @var ProductInterface
     */
    protected $product;

    /**
     * @var ServiceInterface
     */
    protected $service;

    /**
     * 服务费用
     *
     * @var int
     */
    protected $servicePrice = 0;

    /**
     * @var int
     */
    protected $adjustmentsTotal = 0;

    public function __construct()
    {
        $this->adjustments = new ArrayCollection();
        $this->units = new ArrayCollection();
    }

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
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * {@inheritdoc}
     */
    public function setQuantity($quantity)
    {
        return $this->quantity = $quantity;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder(): ?OrderInterface
    {
        return $this->order;
    }

    /**
     * {@inheritdoc}
     */
    public function setOrder(OrderInterface $order = null)
    {
        $currentOrder = $this->getOrder();
        if ($currentOrder === $order) {
            return;
        }

        $this->order = null;

        if (null !== $currentOrder) {
            $currentOrder->removeItem($this);
        }

        if (null === $order) {
            return;
        }

        $this->order = $order;

        if (!$order->hasItem($this)) {
            $order->addItem($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getUnitPrice(): int
    {
        return $this->unitPrice;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function setUnitPrice($unitPrice): void
    {
        Assert::integer($unitPrice, 'Unit price must be an integer.');

        $this->unitPrice = $unitPrice;
        $this->recalculateUnitsTotal();
    }

    /**
     * {@inheritdoc}
     */
    public function recalculateAdjustmentsTotal(): void
    {
        $this->adjustmentsTotal = 0;

        foreach ($this->adjustments as $adjustment) {
            if (!$adjustment->isNeutral()) {
                $this->adjustmentsTotal += $adjustment->getAmount();
            }
        }

        $this->recalculateTotal();
    }

    /**
     * {@inheritdoc}
     */
    public function recalculateUnitsTotal(): void
    {
        $this->unitsTotal = $this->unitPrice * $this->quantity;

        $this->recalculateTotal();
    }

    /**
     * {@inheritdoc}
     */
    public function isImmutable(): bool
    {
        return $this->immutable;
    }

    /**
     * {@inheritdoc}
     */
    public function setImmutable(bool $immutable): void
    {
        $this->immutable = (bool) $immutable;
    }

    /**
     * {@inheritdoc}
     */
    public function getAdjustments(?string $type = null)
    {
        if (null === $type) {
            return $this->adjustments;
        }

        return $this->adjustments->filter(function (AdjustmentInterface $adjustment) use ($type) {
            return $type === $adjustment->getType();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function addAdjustment(AdjustmentInterface $adjustment): void
    {
        if (!$this->hasAdjustment($adjustment)) {
            $this->adjustments->add($adjustment);
            $this->addToAdjustmentsTotal($adjustment);
            $adjustment->setAdjustable($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeAdjustment(AdjustmentInterface $adjustment): void
    {
        if (!$adjustment->isLocked() && $this->hasAdjustment($adjustment)) {
            $this->adjustments->removeElement($adjustment);
            $this->subtractFromAdjustmentsTotal($adjustment);
            $adjustment->setAdjustable(null);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasAdjustment(AdjustmentInterface $adjustment)
    {
        return $this->adjustments->contains($adjustment);
    }

    /**
     * {@inheritdoc}
     */
    public function getAdjustmentsTotal(?string $type = null): int
    {
        if (null === $type) {
            return $this->adjustmentsTotal;
        }

        $total = 0;
        foreach ($this->getAdjustments($type) as $adjustment) {
            if (!$adjustment->isNeutral()) {
                $total += $adjustment->getAmount();
            }
        }

        return $total;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAdjustments(string $type): void
    {
        foreach ($this->getAdjustments($type) as $adjustment) {
            $this->removeAdjustment($adjustment);
        }
    }

    /**
     *  Recalculates total after units total or adjustments total change.
     */
    protected function recalculateTotal(): void
    {
        $this->total = $this->unitsTotal + $this->adjustmentsTotal;

        if ($this->total < 0) {
            $this->total = 0;
        }

        if (null !== $this->order) {
            $this->order->recalculateItemsTotal();
        }
    }

    protected function addToAdjustmentsTotal(AdjustmentInterface $adjustment): void
    {
        if (!$adjustment->isNeutral()) {
            $this->adjustmentsTotal += $adjustment->getAmount();
            $this->recalculateTotal();
        }
    }

    protected function subtractFromAdjustmentsTotal(AdjustmentInterface $adjustment): void
    {
        if (!$adjustment->isNeutral()) {
            $this->adjustmentsTotal -= $adjustment->getAmount();
            $this->recalculateTotal();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function equals(OrderItemInterface $item): bool
    {
        return $this === $orderItem || ($item instanceof static && $item->getProduct() === $this->getProduct() && $item->getService() === $this->getService());
    }

    /**
     * @return mixed
     */
    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getService(): ServiceInterface
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService(ServiceInterface $service): void
    {
        $this->service = $service;
        $this->servicePrice = $service->getPrice();
    }

    /**
     * {@inheritdoc}
     */
    public function getTotal(): int
    {
        return $this->total + $this->getServicePrice();
    }

    /**
     * 获取工时费
     *
     * {@inheritdoc}
     */
    public function getServicePriceAdjustmentsTotal(): int
    {
        $serviceTotal = 0;

        foreach ($this->getAdjustments(AdjustmentInterface::SERVICE_TYPE_ADJUSTMENT) as $adjustment) {
            $serviceTotal += $adjustment->getAmount();
        }

        return $serviceTotal;
    }

    public function getServicePrice(): int
    {
        return $this->servicePrice;
    }

    public function getStockable(): ?StockableInterface
    {
        return $this->getProduct();
    }
}
