<?php


declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\Factory;

use Pintushi\Bundle\PaymentBundle\Entity\PaymentInterface;
use Pintushi\Bundle\PaymentBundle\Entity\Payment;

final class PaymentFactory implements PaymentFactoryInterface
{

    /**
     * {@inheritdoc}
     */
    public function createNew(): PaymentInterface
    {
        return new Payment();
    }

    /**
     * {@inheritdoc}
     */
    public function createWithAmountAndCurrencyCode(int $amount, string $currency): PaymentInterface
    {
        /** @var PaymentInterface $payment */
        $payment = $this->createNew();
        $payment->setAmount($amount);
        $payment->setCurrencyCode($currency);

        return $payment;
    }
}
