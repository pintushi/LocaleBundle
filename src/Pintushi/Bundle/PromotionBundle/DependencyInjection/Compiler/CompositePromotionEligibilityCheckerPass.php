<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class CompositePromotionEligibilityCheckerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('pintushi.promotion_eligibility_checker')) {
            return;
        }

        $container->getDefinition('pintushi.promotion_eligibility_checker')->setArguments([
            array_map(
                function ($id) {
                    return new Reference($id);
                },
                array_keys($container->findTaggedServiceIds('pintushi.promotion_eligibility_checker'))
            ),
        ]);
    }
}
