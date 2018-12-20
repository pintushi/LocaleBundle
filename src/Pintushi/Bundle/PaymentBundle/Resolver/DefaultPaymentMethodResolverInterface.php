<?php


declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\Resolver;

use Pintushi\Bundle\PaymentBundle\Entity\PaymentInterface;
use Pintushi\Bundle\PaymentBundle\Entity\PaymentMethodInterface;

interface DefaultPaymentMethodResolverInterface
{
    /**
     * @param PaymentInterface $payment
     *
     * @return PaymentMethodInterface
     */
    public function getDefaultPaymentMethod(PaymentInterface $payment): PaymentMethodInterface;
}
