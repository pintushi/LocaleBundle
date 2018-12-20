<?php

namespace Pintushi\Bundle\OrderBundle\Entity;

use Pintushi\Bundle\UserBundle\Entity\TimestampableTrait;
use Webmozart\Assert\Assert;

class Adjustment implements AdjustmentInterface
{
    use TimestampableTrait;

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @var OrderItemInterface
     */
    protected $orderItem;

    /**
     * @var OrderItemUnitInterface
     */
    protected $orderItemUnit;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var int
     */
    protected $amount = 0;

    /**
     * @var bool
     */
    protected $neutral = false;

    /**
     * @var bool
     */
    protected $locked = false;

    /**
     * @var string
     */
    protected $origin;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
    public function getAdjustable(): ?AdjustableInterface
    {
        if (null !== $this->order) {
            return $this->order;
        }

        if (null !== $this->orderItem) {
            return $this->orderItem;
        }

        if (null !== $this->orderItemUnit) {
            return $this->orderItemUnit;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setAdjustable(AdjustableInterface $adjustable = null): void
    {
        $this->assertNotLocked();

        $currentAdjustable = $this->getAdjustable();
        if ($currentAdjustable === $adjustable) {
            return;
        }

        $this->order = $this->orderItem = $this->orderItemUnit = null;
        if (null !== $currentAdjustable) {
            $currentAdjustable->removeAdjustment($this);
        }

        if (null === $adjustable) {
            return;
        }

        $this->assignAdjustable($adjustable);
        $adjustable->addAdjustment($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * {@inheritdoc}
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function setAmount(int $amount): void
    {
        Assert::integer($amount, 'Amount must be an integer.');

        $this->amount = $amount;
        if (!$this->isNeutral()) {
            $this->recalculateAdjustable();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isNeutral():bool
    {
        return $this->neutral;
    }

    /**
     * {@inheritdoc}
     */
    public function setNeutral(bool $neutral): void
    {
        $neutral = (bool) $neutral;

        if ($this->neutral !== $neutral) {
            $this->neutral = $neutral;
            $this->recalculateAdjustable();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isLocked(): bool
    {
        return $this->locked;
    }

    public function lock(): void
    {
        $this->locked = true;
    }

    public function unlock(): void
    {
        $this->locked = false;
    }

    /**
     * {@inheritdoc}
     */
    public function isCharge(): bool
    {
        return 0 > $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredit(): bool
    {
        return 0 < $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrigin(): string
    {
        return $this->origin;
    }

    /**
     * {@inheritdoc}
     */
    public function setOrigin(string $origin): void
    {
        $this->origin = $origin;
    }

    private function recalculateAdjustable(): void
    {
        $adjustable = $this->getAdjustable();
        if (null !== $adjustable) {
            $adjustable->recalculateAdjustmentsTotal();
        }
    }

    private function assignAdjustable(AdjustableInterface $adjustable): void
    {
        if ($adjustable instanceof OrderInterface) {
            $this->order = $adjustable;

            return;
        }

        if ($adjustable instanceof OrderItemInterface) {
            $this->orderItem = $adjustable;

            return;
        }

        if ($adjustable instanceof OrderItemUnitInterface) {
            $this->orderItemUnit = $adjustable;

            return;
        }

        throw new \InvalidArgumentException('Given adjustable object class is not supported.');
    }

    private function assertNotLocked(): void
    {
        if ($this->isLocked()) {
            throw new \LogicException('Adjustment is locked and cannot be modified.');
        }
    }
}
