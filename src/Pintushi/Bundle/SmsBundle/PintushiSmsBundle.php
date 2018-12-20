<?php

namespace Pintushi\Bundle\SmsBundle;

use Pintushi\Bundle\SmsBundle\DependencyInjection\Security\Factory\SmsAuthFactory;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Pintushi\Bundle\SmsBundle\DependencyInjection\Compiler\RegisterSmsGatewayPass;

/**
 * @author Vidy Videni<videni@foxmail.com>
 */
class PintushiSmsBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterSmsGatewayPass());

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new SmsAuthFactory());
    }
}
