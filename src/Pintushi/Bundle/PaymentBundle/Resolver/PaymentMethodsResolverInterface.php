<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\Resolver;

use Pintushi\Bundle\PaymentBundle\Entity\PaymentInterface;
use Pintushi\Bundle\PaymentBundle\Entity\PaymentMethodInterface;

interface PaymentMethodsResolverInterface
{
    /**
     * @param PaymentInterface $subject
     *
     * @return PaymentMethodInterface[]
     */
    public function getSupportedMethods(PaymentInterface $subject): array;

    /**
     * @param PaymentInterface $subject
     *
     * @return bool
     */
    public function supports(PaymentInterface $subject): bool;
}
