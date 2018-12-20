<?php


declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle;

use Pintushi\Bundle\PromotionBundle\DependencyInjection\Compiler\CompositePromotionCouponEligibilityCheckerPass;
use Pintushi\Bundle\PromotionBundle\DependencyInjection\Compiler\CompositePromotionEligibilityCheckerPass;
use Pintushi\Bundle\PromotionBundle\DependencyInjection\Compiler\RegisterPromotionActionsPass;
use Pintushi\Bundle\PromotionBundle\DependencyInjection\Compiler\RegisterRuleCheckersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class PintushiPromotionBundle extends Bundle
{

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new CompositePromotionEligibilityCheckerPass());
        $container->addCompilerPass(new CompositePromotionCouponEligibilityCheckerPass());

        $container->addCompilerPass(new RegisterRuleCheckersPass());
        $container->addCompilerPass(new RegisterPromotionActionsPass());
    }
}
