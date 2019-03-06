<?php

namespace Pintushi\Bundle\GridBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ConfigurationPass implements CompilerPassInterface
{
    const BUILDER_SERVICE_ID        = 'pintushi_grid.grid.builder';
    const PROVIDER_SERVICE_ID       = 'pintushi_grid.configuration.provider';
    const CHAIN_PROVIDER_SERVICE_ID = 'pintushi_grid.configuration.provider.chain';

    const SOURCE_TAG_NAME    = 'pintushi_grid.datasource';
    const EXTENSION_TAG_NAME = 'pintushi_grid.extension';
    const PROVIDER_TAG_NAME  = 'pintushi_grid.configuration.provider';

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->registerConfigProviders($container);
        $this->registerDataSources($container);
    }

    /**
     * Register all grid configuration providers
     *
     * @param ContainerBuilder $container
     */
    protected function registerConfigProviders(ContainerBuilder $container)
    {
        if ($container->hasDefinition(self::CHAIN_PROVIDER_SERVICE_ID)) {
            $providers = [];
            foreach ($container->findTaggedServiceIds(self::PROVIDER_TAG_NAME) as $id => $attributes) {
                $priority = isset($attributes[0]['priority']) ? $attributes[0]['priority'] : 0;
                $providers[$priority][] = new Reference($id);
            }
            if (!empty($providers)) {
                // sort by priority and flatten
                krsort($providers);
                $providers = call_user_func_array('array_merge', $providers);
                // add to chain provider
                $chainConfigProviderDef = $container->getDefinition(self::CHAIN_PROVIDER_SERVICE_ID);
                foreach ($providers as $provider) {
                    $chainConfigProviderDef->addMethodCall('addProvider', [$provider]);
                }
            }
        }
    }

    /**
     * Find and add available datasources and extensions to grid builder
     *
     * @param ContainerBuilder $container
     */
    protected function registerDataSources(ContainerBuilder $container)
    {
        if ($container->hasDefinition(self::BUILDER_SERVICE_ID)) {
            $builderDef = $container->getDefinition(self::BUILDER_SERVICE_ID);
            $sources = $container->findTaggedServiceIds(self::SOURCE_TAG_NAME);
            foreach ($sources as $serviceId => $tags) {
                $tagAttrs = reset($tags);
                $builderDef->addMethodCall('registerDatasource', [$tagAttrs['type'], new Reference($serviceId)]);
            }

            $extensions = $container->findTaggedServiceIds(self::EXTENSION_TAG_NAME);
            foreach ($extensions as $serviceId => $tags) {
                $builderDef->addMethodCall('registerExtension', [new Reference($serviceId)]);
            }
        }
    }
}
