<?php

namespace Pintushi\Bundle\OrderBundle;

use Pintushi\Bundle\OrderBundle\DependencyInjection\Compiler\RegisterCartContextsPass;
use Pintushi\Bundle\OrderBundle\DependencyInjection\Compiler\RegisterProcessorsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class PintushiOrderBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterProcessorsPass());
        $container->addCompilerPass(new RegisterCartContextsPass());
    }
}
