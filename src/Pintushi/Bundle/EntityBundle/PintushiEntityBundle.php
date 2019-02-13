<?php

namespace Pintushi\Bundle\EntityBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Pintushi\Bundle\EntityBundle\DependencyInjection\Compiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PintushiEntityBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new Compiler\EntityNameProviderPass());
        $container->addCompilerPass(new Compiler\EntityAliasProviderPass());
    }
}
