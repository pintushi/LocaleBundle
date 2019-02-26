<?php

namespace Pintushi\Bundle\FilterBundle;

use Pintushi\Bundle\FilterBundle\DependencyInjection\Compiler\FilterTypesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PintushiFilterBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FilterTypesPass());
    }
}
