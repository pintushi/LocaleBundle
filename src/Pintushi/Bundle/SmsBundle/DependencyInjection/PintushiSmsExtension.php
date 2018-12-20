<?php

namespace Pintushi\Bundle\SmsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Overtrue\EasySms\EasySms;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Vidy Videni<videni@foxmail.com>
 */
class PintushiSmsExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $smsDefinition = new Definition(EasySms::class, [
            [
                'timeout' => $config['timeout'],
                'default' => [
                    'gateways' => $config['enabled_gateways']
                ],
                'gateways' => $config['gateways']
            ]
        ]);

        $container->setDefinition('pintushi.sms', $smsDefinition);
        $container->setParameter('pintushi.sms_templates', $config['templates']);
    }
}
