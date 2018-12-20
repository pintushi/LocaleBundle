<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\Entity;

use Pintushi\Bundle\UserBundle\Entity\TimestampableInterface;

interface PaymentInterface extends TimestampableInterface
{
    public const STATE_AUTHORIZED = 'authorized';
    public const STATE_CART = 'cart';
    public const STATE_NEW = 'new';
    public const STATE_PROCESSING = 'processing';
    public const STATE_COMPLETED = 'completed';
    public const STATE_FAILED = 'failed';
    public const STATE_CANCELLED = 'cancelled';
    public const STATE_REFUNDED = 'refunded';
    public const STATE_UNKNOWN = 'unknown';

    /**
     * @return PaymentMethodInterface
     */
    public function getMethod(): ?PaymentMethodInterface;

    /**
     * @param PaymentMethodInterface|null $method
     */
    public function setMethod(?PaymentMethodInterface $method): void;

    /**
     * @return string|null
     */
    public function getState(): ?string;

    /**
     * @param string $state
     */
    public function setState(string $state): void;

    /**
     * @return string|null
     */
    public function getCurrencyCode(): ?string;

    /**
     * @param string $currencyCode
     */
    public function setCurrencyCode(string $currencyCode): void;

    /**
     * @return int|null
     */
    public function getAmount(): ?int;

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void;

    /**
     * @return array
     */
    public function getDetails(): array;

    /**
     * @param array $details
     */
    public function setDetails(array $details): void;
}
