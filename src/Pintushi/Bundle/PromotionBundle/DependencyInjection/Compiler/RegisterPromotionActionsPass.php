<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterPromotionActionsPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('pintushi.registry_promotion_action') || !$container->has('pintushi.form_registry.promotion_action')) {
            return;
        }

        $promotionActionRegistry = $container->getDefinition('pintushi.registry_promotion_action');
        $promotionActionFormTypeRegistry = $container->getDefinition('pintushi.form_registry.promotion_action');

        $promotionActionTypeToLabelMap = [];
        foreach ($container->findTaggedServiceIds('pintushi.promotion_action') as $id => $attributes) {
            if (!isset($attributes[0]['type'], $attributes[0]['label'], $attributes[0]['form_type'])) {
                throw new \InvalidArgumentException('Tagged promotion action `' . $id . '` needs to have `type`, `form_type` and `label` attributes.');
            }

            $promotionActionTypeToLabelMap[$attributes[0]['type']] = $attributes[0]['label'];
            $promotionActionRegistry->addMethodCall('register', [$attributes[0]['type'], new Reference($id)]);

            $promotionActionFormTypeRegistry->addMethodCall('add', [$attributes[0]['type'], 'default', $attributes[0]['form_type']]);
        }

        $container->setParameter('pintushi.promotion_actions', $promotionActionTypeToLabelMap);
    }
}