<?php


declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterPaymentMethodsResolversPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('pintushi.registry.payment_methods_resolver')) {
            return;
        }

        $registry = $container->findDefinition('pintushi.registry.payment_methods_resolver');
        $resolvers = [];

        foreach ($container->findTaggedServiceIds('pintushi.payment_method_resolver') as $id => $attributes) {
            if (!isset($attributes[0]['type']) || !isset($attributes[0]['label'])) {
                throw new \InvalidArgumentException('Tagged payment methods resolvers need to have `type` and `label` attributes.');
            }

            $priority = (int) ($attributes[0]['priority'] ?? 0);

            $resolvers[$attributes[0]['type']] = $attributes[0]['label'];

            $registry->addMethodCall('register', [new Reference($id), $priority]);
        }

        $container->setParameter('pintushi.payment_method_resolvers', $resolvers);
    }
}
