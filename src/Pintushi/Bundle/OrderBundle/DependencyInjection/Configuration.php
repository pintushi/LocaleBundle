<?php

namespace Pintushi\Bundle\OrderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pintushi_order');

        $this->addExpirationPeriodsSection($rootNode);

        return $treeBuilder;
    }

    private function addExpirationPeriodsSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('expiration')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('cart')->defaultValue('2 days')->cannotBeEmpty()->end()
                        ->scalarNode('order')->defaultValue('5 days')->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
