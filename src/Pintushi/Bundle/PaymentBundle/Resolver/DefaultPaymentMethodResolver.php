<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\Resolver;

use Pintushi\Bundle\PaymentBundle\Exception\UnresolvedDefaultPaymentMethodException;
use Pintushi\Bundle\PaymentBundle\Entity\PaymentInterface;
use Pintushi\Bundle\PaymentBundle\Entity\PaymentMethodInterface;
use Pintushi\Bundle\PaymentBundle\Repository\PaymentMethodRepositoryInterface;

final class DefaultPaymentMethodResolver implements DefaultPaymentMethodResolverInterface
{
    /**
     * @var PaymentMethodRepositoryInterface
     */
    private $paymentMethodRepository;

    /**
     * @param PaymentMethodRepositoryInterface $paymentMethodRepository
     */
    public function __construct(PaymentMethodRepositoryInterface $paymentMethodRepository)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    /**
     * {@inheritdoc}
     *
     * @throws UnresolvedDefaultPaymentMethodException
     */
    public function getDefaultPaymentMethod(PaymentInterface $payment): PaymentMethodInterface
    {
        $paymentMethods = $this->paymentMethodRepository->findBy(['enabled' => true]);
        if (empty($paymentMethods)) {
            throw new UnresolvedDefaultPaymentMethodException();
        }

        return $paymentMethods[0];
    }
}
