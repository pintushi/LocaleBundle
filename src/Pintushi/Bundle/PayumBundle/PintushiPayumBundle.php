<?php
declare(strict_types=1);

namespace Pintushi\Bundle\PayumBundle;

use Pintushi\Bundle\PayumBundle\DependencyInjection\Compiler\RegisterGatewayConfigTypePass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class PintushiPayumBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterGatewayConfigTypePass());
    }
}
