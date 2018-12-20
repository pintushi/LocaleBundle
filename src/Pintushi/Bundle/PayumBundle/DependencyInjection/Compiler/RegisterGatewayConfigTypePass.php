<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PayumBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterGatewayConfigTypePass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('pintushi.form_registry.payum_gateway_config')) {
            return;
        }

        $formRegistry = $container->findDefinition('pintushi.form_registry.payum_gateway_config');
        $gatewayFactories = [];

        $gatewayConfigurationTypes = $container->findTaggedServiceIds('pintushi.gateway_configuration_type');

        foreach ($gatewayConfigurationTypes as $id => $attributes) {
            if (!isset($attributes[0]['type']) || !isset($attributes[0]['label'])) {
                throw new \InvalidArgumentException('Tagged gateway configuration type needs to have `type` and `label` attributes.');
            }

            $gatewayFactories[$attributes[0]['type']] = $attributes[0]['label'];

            $formRegistry->addMethodCall(
                'add',
                ['gateway_config', $attributes[0]['type'], $container->getDefinition($id)->getClass()]
            );
        }

        $gatewayFactories = array_merge($gatewayFactories, ['offline' => 'pintushi.payum_gateway_factory.offline']);
        ksort($gatewayFactories);

        $container->setParameter('pintushi.gateway_factories', $gatewayFactories);
    }
}
