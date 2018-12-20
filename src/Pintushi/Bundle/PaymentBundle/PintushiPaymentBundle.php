<?php

namespace Pintushi\Bundle\PaymentBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Pintushi\Bundle\PaymentBundle\DependencyInjection\Compiler\RegisterPaymentMethodsResolversPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PintushiPaymentBundle extends Bundle
{
     /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterPaymentMethodsResolversPass());
    }
}
