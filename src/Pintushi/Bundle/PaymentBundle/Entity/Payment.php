<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\Entity;

use Pintushi\Bundle\UserBundle\Entity\TimestampableTrait;
use Webmozart\Assert\Assert;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;

class Payment implements PaymentInterface
{
    use TimestampableTrait;

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var PaymentMethodInterface
     */
    protected $method;

    /**
     * @var string
     */
    protected $currencyCode;

    /**
     * @var int
     */
    protected $amount = 0;

    /**
     * @var string
     */
    protected $state = PaymentInterface::STATE_CART;

    /**
     * @var array
     */
    protected $details = [];

    /**
     * @var BaseOrderInterface
     */
    protected $order;

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
    public function getMethod(): ?PaymentMethodInterface
    {
        return $this->method;
    }

    /**
     * {@inheritdoc}
     */
    public function setMethod(?PaymentMethodInterface $method): void
    {
        $this->method = $method;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setCurrencyCode(string $currencyCode): void
    {
        Assert::string($currencyCode);

        $this->currencyCode = $currencyCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * {@inheritdoc}
     */
    public function getState(): ?string
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
    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * {@inheritdoc}
     */
    public function setDetails(array $details): void
    {
        $this->details = $details;
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
    public function setOrder(?OrderInterface $order): void
    {
        $this->order = $order;
    }
}
