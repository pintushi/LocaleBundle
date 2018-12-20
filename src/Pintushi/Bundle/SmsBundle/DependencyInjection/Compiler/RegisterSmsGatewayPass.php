<?php
declare(strict_types=1);

namespace Pintushi\Bundle\SmsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterSmsGatewayPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('pintushi.form_registry.sms_gateway_configuration') || !$container->has('pintushi.form_registry.sms_gateway')) {
            return;
        }

        $gatewayRegistry = $container->getDefinition('pintushi.form_registry.sms_gateway_configuration');
        $gatewayFormTypeRegistry = $container->getDefinition('pintushi.form_registry.sms_gateway');

        $smsGatewayToLabelMap = [];
        foreach ($container->findTaggedServiceIds('pintushi.sms_gateway') as $id => $attributes) {
            if (!isset($attributes[0]['gateway'], $attributes[0]['label'])) {
                throw new \InvalidArgumentException('Tagged SMS gateway`' . $id . '` needs to have `gateway` and `label` attributes.');
            }

            $gatewayFormTypeRegistry->addMethodCall('add', [$attributes[0]['gateway'], 'default', $id]);
            $smsGatewayToLabelMap[$attributes[0]['gateway']] = $attributes[0]['label'];
            $gatewayRegistry->addMethodCall('register', [$attributes[0]['gateway'], new Reference($id)]);
        }

        $container->setParameter('pintushi.sms_gateways', $smsGatewayToLabelMap);
    }
}
