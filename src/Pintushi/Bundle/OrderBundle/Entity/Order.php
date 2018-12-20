<?php

namespace Pintushi\Bundle\OrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Pintushi\Bundle\UserBundle\Entity\TimestampableTrait;
use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Pintushi\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Pintushi\Bundle\ReportBundle\Entity\ReportInterface;
use Pintushi\Bundle\CustomerBundle\Entity\CustomerInterface;
use Pintushi\Bundle\PaymentBundle\Entity\PaymentInterface;
use Webmozart\Assert\Assert;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionCouponInterface;
use Pintushi\Bundle\OrderBundle\OrderShippingStates;
use Pintushi\Bundle\OrderBundle\OrderPaymentStates;
use Pintushi\Bundle\AddressBundle\Entity\AddressInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;
use Pintushi\Bundle\ReviewBundle\Entity\ReviewInterface;
use Pintushi\Bundle\ReviewBundle\Entity\ReviewableInterface;
use Pintushi\Bundle\ShippingBundle\Entity\ShipmentInterface;

/**
 * @Config(
 *  defaultValues={
 *      "security"={
 *          "type"="ACL",
 *          "group_name"="commerce",
 *          "category"="orders",
 *      },
 *     "ownership"={
 *          "owner_type"="USER",
 *          "owner_field_name"="customer",
 *          "owner_column_name"="customer_id",
 *          "organization_field_name"="organization",
 *      }
 *   }
 * )
 */
class Order implements OrderInterface, OrganizationAwareInterface, PromotionSubjectInterface, ReviewableInterface
{
    use TimestampableTrait, OrganizationAwareTrait;

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     */
    protected $number;

    /**
     * @var string
     */
    protected $notes;

    /**
     * @var Collection|OrderItemInterface[]
     */
    protected $items;

    /**
     * @var int
     */
    protected $itemsTotal = 0;

    /**
     * @var Collection|AdjustmentInterface[]
     */
    protected $adjustments;

    /**
     * @var int
     */
    protected $adjustmentsTotal = 0;

    /**
     * Items total + adjustments total.
     *
     * @var int
     */
    protected $total = 0;

    /**
     * @var string
     */
    protected $state = OrderInterface::STATE_CART;

    protected $report;

    protected $customer;

    protected $auto;

    protected $payment;

    protected $promotions;

    protected $shipment;

    protected $promotionCoupon;

    /**
     * @var string
     */
    protected $paymentState = OrderPaymentStates::STATE_CART;

    /**
     * @var string
     */
    protected $shippingState = OrderShippingStates::STATE_CART;

    protected $shippingAddress;

    protected $appointmentDate;

    protected $review;

    protected $tokenValue;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->adjustments = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->promotions = new ArrayCollection();
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
    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * {@inheritdoc}
     */
    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    /**
     * {@inheritdoc}
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }

    /**
     * {@inheritdoc}
     */
    public function setNotes(string $notes): void
    {
        $this->notes = $notes;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function clearItems(): void
    {
        $this->items->clear();

        $this->recalculateItemsTotal();
    }

    /**
     * {@inheritdoc}
     */
    public function countItems(): int
    {
        return $this->items->count();
    }

    /**
     * {@inheritdoc}
     */
    public function addItem(OrderItemInterface $item): void
    {
        if ($this->hasItem($item)) {
            return;
        }

        $this->itemsTotal += $item->getTotal();
        $this->items->add($item);
        $item->setOrder($this);

        $this->recalculateTotal();
    }

    /**
     * {@inheritdoc}
     */
    public function removeItem(OrderItemInterface $item): void
    {
        if ($this->hasItem($item)) {
            $this->items->removeElement($item);
            $this->itemsTotal -= $item->getTotal();
            $this->recalculateTotal();
            $item->setOrder(null);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasItem(OrderItemInterface $item): bool
    {
        return $this->items->contains($item);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemsTotal(): int
    {
        return $this->itemsTotal;
    }

    public function recalculateItemsTotal(): void
    {
        $this->itemsTotal = 0;
        foreach ($this->items as $item) {
            $this->itemsTotal += $item->getTotal();
        }

        $this->recalculateTotal();
    }

    /**
     * {@inheritdoc}
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalQuantity(): int
    {
        $quantity = 0;

        foreach ($this->items as $item) {
            $quantity += $item->getQuantity();
        }

        return $quantity;
    }

    /**
     * {@inheritdoc}
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * {@inheritdoc}
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
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
    public function getAdjustmentsRecursively(?string $type): array
    {
        $adjustments = $this->getAdjustments($type)->toArray();
        foreach ($this->items as $item) {
            if (($itemAdjustments= $item->getAdjustments($type) && !empty($itemAdjustments))) {
                $adjustments = array_merge($adjustments, $itemAdjustments);
            }
        }

        return $adjustments;
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
    public function getAdjustmentsTotalRecursively(?string $type): int
    {
        $total = 0;
        foreach ($this->getAdjustmentsRecursively($type) as $adjustment) {
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
            if ($adjustment->isLocked()) {
                continue;
            }

            $this->removeAdjustment($adjustment);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeAdjustmentsRecursively(?string $type): void
    {
        $this->removeAdjustments($type);
        foreach ($this->items as $item) {
            $item->removeAdjustments($type);
        }
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
     * Items total + Adjustments total.
     */
    protected function recalculateTotal(): void
    {
        $this->total = $this->itemsTotal + $this->adjustmentsTotal;

        if ($this->total < 0) {
            $this->total = 0;
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
    public function getReport(): ?ReportInterface
    {
        return $this->report;
    }

    /**
     * {@inheritdoc}
     */
    public function setReport(?ReportInterface $report): void
    {
        $this->report = $report;
    }

      /**
     * {@inheritdoc}
     */
    public function getCustomer(): ?CustomerInterface
    {
        return $this->customer;
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomer(?CustomerInterface $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return mixed
     */
    public function getAuto()
    {
        return $this->auto;
    }

    /**
     * @param mixed $auto
     *
     * @return self
     */
    public function setAuto($auto)
    {
        $this->auto = $auto;

        return $this;
    }

     /**
     * {@inheritdoc}
     */
    public function hasPromotion(PromotionInterface $promotion): bool
    {
        return $this->promotions->contains($promotion);
    }

    /**
     * {@inheritdoc}
     */
    public function addPromotion(PromotionInterface $promotion): void
    {
        if (!$this->hasPromotion($promotion)) {
            $this->promotions->add($promotion);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removePromotion(PromotionInterface $promotion): void
    {
        if ($this->hasPromotion($promotion)) {
            $this->promotions->removeElement($promotion);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    /**
     * @return mixed
     */
    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * @param mixed $shipment
     *
     * @return self
     */
    public function setShipment(?ShipmentInterface $shipment)
    {
        $this->shipment = $shipment;
        if ($shipment) {
            $shipment->setOrder($this);
        }

        return $this;
    }

     /**
     * {@inheritdoc}
     */
    public function getPromotionCoupon(): ?PromotionCouponInterface
    {
        return $this->promotionCoupon;
    }

    /**
     * {@inheritdoc}
     */
    public function setPromotionCoupon(?PromotionCouponInterface $coupon): void
    {
        $this->promotionCoupon = $coupon;
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingAddress(): ?AddressInterface
    {
        return $this->shippingAddress;
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingAddress(?AddressInterface $address): void
    {
        $this->shippingAddress = $address;
    }

        /**
     * {@inheritdoc}
     */
    public function getPromotionSubjectTotal(): int
    {
        return $this->getItemsTotal();
    }

    /**
     * Returns amount of order discount. Does not include order item and shipping discounts.
     *
     * {@inheritdoc}
     */
    public function getOrderPromotionTotal(): int
    {
        $orderPromotionTotal = 0;

        foreach ($this->items as $item) {
            $orderPromotionTotal += $item->getAdjustmentsTotal(AdjustmentInterface::ORDER_PROMOTION_ADJUSTMENT);
        }

        return $orderPromotionTotal;
    }

    public function getShippingTotal(): int
    {
        $shippingTotal = $this->getAdjustmentsTotal(AdjustmentInterface::SHIPPING_ADJUSTMENT);
        $shippingTotal += $this->getAdjustmentsTotal(AdjustmentInterface::ORDER_SHIPPING_PROMOTION_ADJUSTMENT);

        return $shippingTotal;
    }

    /**
     * {@inheritdoc}
     */
    public function getPromotionSubjectCount(): int
    {
        return $this->getTotalQuantity();
    }

    /**
     * @return mixed
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param mixed $payment
     *
     * @return self
     */
    public function setPayment(?PaymentInterface $payment)
    {
        $this->payment = $payment;
        if ($payment) {
            $payment->setOrder($this);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAppointmentDate()
    {
        return $this->appointmentDate;
    }

    /**
     * @param mixed $appointmentDate
     *
     * @return self
     */
    public function setAppointmentDate($appointmentDate)
    {
        $this->appointmentDate = $appointmentDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @param mixed $review
     *
     * @return self
     */
    public function setReview($review)
    {
        $this->review = $review;

        return $this;
    }

    public function getName():?string
    {
        return $this->auto ? $this->auto->getFullName(): null;
    }

    /**
     * @return string
     */
    public function getShippingState()
    {
        return $this->shippingState;
    }

    /**
     * @param string $shippingState
     *
     * @return self
     */
    public function setShippingState($shippingState)
    {
        $this->shippingState = $shippingState;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentState()
    {
        return $this->paymentState;
    }

    /**
     * @param string $paymentState
     *
     * @return self
     */
    public function setPaymentState($paymentState)
    {
        $this->paymentState = $paymentState;

        return $this;
    }

     /**
     * {@inheritdoc}
     */
    public function getTokenValue(): ?string
    {
        return $this->tokenValue;
    }

    /**
     * {@inheritdoc}
     */
    public function setTokenValue(?string $tokenValue): void
    {
        $this->tokenValue = $tokenValue;
    }
}
