<?php
declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterRuleCheckersPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('pintushi.registry_promotion_rule_checker') || !$container->has('pintushi.form_registry.promotion_rule_checker')) {
            return;
        }

        $promotionRuleCheckerRegistry = $container->getDefinition('pintushi.registry_promotion_rule_checker');
        $promotionRuleCheckerFormTypeRegistry = $container->getDefinition('pintushi.form_registry.promotion_rule_checker');

        $promotionRuleCheckerTypeToLabelMap = [];
        foreach ($container->findTaggedServiceIds('pintushi.promotion_rule_checker') as $id => $attributes) {
            if (!isset($attributes[0]['type'], $attributes[0]['label'], $attributes[0]['form_type'])) {
                throw new \InvalidArgumentException('Tagged rule checker `' . $id . '` needs to have `type`, `form_type` and `label` attributes.');
            }

            $promotionRuleCheckerTypeToLabelMap[$attributes[0]['type']] = $attributes[0]['label'];
            $promotionRuleCheckerRegistry->addMethodCall('register', [$attributes[0]['type'], new Reference($id)]);
            $promotionRuleCheckerFormTypeRegistry->addMethodCall('add', [$attributes[0]['type'], 'default', $attributes[0]['form_type']]);
        }

        $container->setParameter('pintushi.promotion_rules', $promotionRuleCheckerTypeToLabelMap);
    }
}
