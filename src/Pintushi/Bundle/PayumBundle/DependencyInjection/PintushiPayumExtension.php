<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PayumBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class PintushiPayumExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container): void
    {
        $config = $this->processConfiguration($this->getConfiguration([], $container), $config);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));


        $loader->load('services.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        if (!$container->hasExtension('pintushi_payment')) {
            return;
        }

        $gateways = [];
        $configs = $container->getExtensionConfig('payum');
        foreach ($configs as $config) {
            if (!isset($config['gateways'])) {
                continue;
            }

            foreach (array_keys($config['gateways']) as $gatewayKey) {
                $gateways[$gatewayKey] = 'pintushi.payum_gateway.' . $gatewayKey;
            }
        }

        $container->prependExtensionConfig('pintushi_payment', ['gateways' => $gateways]);
    }
}
