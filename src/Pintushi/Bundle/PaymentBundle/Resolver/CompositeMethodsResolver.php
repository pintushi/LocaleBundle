<?php


declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\Resolver;

use Pintushi\Bundle\PaymentBundle\Entity\PaymentInterface;
use Sylius\Component\Registry\PrioritizedServiceRegistryInterface;

final class CompositeMethodsResolver implements PaymentMethodsResolverInterface
{
    /**
     * @var PrioritizedServiceRegistryInterface
     */
    private $resolversRegistry;

    /**
     * @param PrioritizedServiceRegistryInterface $resolversRegistry
     */
    public function __construct(PrioritizedServiceRegistryInterface $resolversRegistry)
    {
        $this->resolversRegistry = $resolversRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedMethods(PaymentInterface $payment): array
    {
        /** @var PaymentMethodsResolverInterface $resolver */
        foreach ($this->resolversRegistry->all() as $resolver) {
            if ($resolver->supports($payment)) {
                return $resolver->getSupportedMethods($payment);
            }
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function supports(PaymentInterface $payment): bool
    {
        /** @var PaymentMethodsResolverInterface $resolver */
        foreach ($this->resolversRegistry->all() as $resolver) {
            if ($resolver->supports($payment)) {
                return true;
            }
        }

        return false;
    }
}
