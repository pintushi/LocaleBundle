<?php

namespace Pintushi\Bundle\EntityConfigBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

use Oro\Component\Config\Loader\CumulativeConfigLoader;
use Oro\Component\Config\Loader\YamlCumulativeFileLoader;

class EntityConfigPass implements CompilerPassInterface
{
    const CONFIG_MANAGER_SERVICE = 'pintushi_entity_config.config_manager';

    const ENTITY_CONFIG_ROOT_NODE = 'entity_config';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $configManagerDefinition = $container->getDefinition(self::CONFIG_MANAGER_SERVICE);

        $configLoader = new CumulativeConfigLoader(
            'pintushi_entity_config',
            new YamlCumulativeFileLoader('Resources/config/app/entity_config.yml')
        );

        $resources = $configLoader->load($container);

        $scopes    = [];
        foreach ($resources as $resource) {
            if (!empty($resource->data[self::ENTITY_CONFIG_ROOT_NODE])) {
                foreach ($resource->data[self::ENTITY_CONFIG_ROOT_NODE] as $scope => $config) {
                    if (!empty($scopes[$scope])) {
                        $scopes[$scope] = array_merge_recursive($scopes[$scope], $config);
                    } else {
                        $scopes[$scope] = $config;
                    }
                }
            }
        }

        foreach ($scopes as $scope => $config) {
            $provider = new Definition('Pintushi\Bundle\EntityConfigBundle\Provider\ConfigProvider');

            $provider->setArguments(
                [
                    new Reference(self::CONFIG_MANAGER_SERVICE),
                    $scope,
                    $config
                ]
            )->setPublic(true);

            $container->setDefinition('pintushi_entity_config.provider.' . $scope, $provider);

            $configManagerDefinition->addMethodCall('addProvider', array($provider));
        }
    }
}
