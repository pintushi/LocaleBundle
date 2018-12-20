<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\Factory;

use Pintushi\Bundle\PaymentBundle\Entity\PaymentInterface;

interface PaymentFactoryInterface
{
    /**
     * @param int $amount
     * @param string $currency
     *
     * @return PaymentInterface
     */
    public function createWithAmountAndCurrencyCode(int $amount, string $currency): PaymentInterface;
}
