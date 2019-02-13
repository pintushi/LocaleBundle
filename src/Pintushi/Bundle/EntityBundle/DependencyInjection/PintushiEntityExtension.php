<?php

namespace Pintushi\Bundle\EntityBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Oro\Component\Config\Loader\CumulativeConfigLoader;
use Oro\Component\Config\Loader\YamlCumulativeFileLoader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Pintushi\Bundle\EntityBundle\Provider\SearchMappingProvider;

class PintushiEntityExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('pintushi_entity.entity_aliases', $config['entity_aliases']);
        $container->setParameter('pintushi_entity.entity_alias_exclusions', $config['entity_alias_exclusions']);
        $container->setParameter('pintushi_entity.entity_name_formats', $config['entity_name_formats']);
        $container->setParameter('pintushi_entity.entity_name_format.default', 'full');
    }

     /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        if (!$container->hasExtension('doctrine_cache')) {
            throw new \RuntimeException(sprintf('DoctrineCacheBundle is required, install it with `composer require "doctrine/doctrine-cache-bundle"`'));
        }

        $configs = $container->getExtensionConfig('pintushi_entity');
        $cacheProvider = $configs[0]['cache_provider'];

        $container->prependExtensionConfig('doctrine_cache', [
            'providers'=> [
                'permission' => [
                    'type' => $cacheProvider,
                    'namespace' => 'pintushi_entity_aliases',
                ],
            ]
        ]);
    }
}
