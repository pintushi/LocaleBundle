<?php

namespace Pintushi\Bundle\CoreBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Oro\Component\DependencyInjection\Compiler\ServiceLinkCompilerPass;

use Pintushi\Bundle\CoreBundle\DependencyInjection\Compiler\AppPass;

final class PintushiCoreBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AppPass());
    }
}
