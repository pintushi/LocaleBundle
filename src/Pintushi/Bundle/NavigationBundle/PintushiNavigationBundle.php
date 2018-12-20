<?php

namespace Pintushi\Bundle\NavigationBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use Pintushi\Bundle\NavigationBundle\DependencyInjection\Compiler\MenuBuilderChainPass;
use Pintushi\Bundle\NavigationBundle\DependencyInjection\Compiler\MenuExtensionPass;
use Pintushi\Bundle\NavigationBundle\Entity\MenuUpdate;

class PintushiNavigationBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new MenuBuilderChainPass());
        $container->addCompilerPass(new MenuExtensionPass());
    }
}
