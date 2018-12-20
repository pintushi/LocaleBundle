<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PayumBundle\Provider;

use Pintushi\Bundle\PaymentBundle\Entity\PaymentInterface;

interface PaymentDescriptionProviderInterface
{
    /**
     * @param PaymentInterface $payment
     *
     * @return string
     */
    public function getPaymentDescription(PaymentInterface $payment): string;
}
