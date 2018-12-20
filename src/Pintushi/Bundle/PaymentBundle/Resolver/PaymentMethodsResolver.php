<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\Resolver;

use Pintushi\Bundle\PaymentBundle\Entity\PaymentInterface;
use Doctrine\Common\Persistence\ObjectRepository;

final class PaymentMethodsResolver implements PaymentMethodsResolverInterface
{
    /**
     * @var ObjectRepository
     */
    private $paymentMethodRepository;

    /**
     * @param ObjectRepository $paymentMethodRepository
     */
    public function __construct(ObjectRepository $paymentMethodRepository)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedMethods(PaymentInterface $payment): array
    {
        return $this->paymentMethodRepository->findBy(['enabled' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(PaymentInterface $payment): bool
    {
        return true;
    }
}
