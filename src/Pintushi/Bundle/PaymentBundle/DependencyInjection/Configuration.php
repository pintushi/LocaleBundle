<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pintushi_payment');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('gateways')
                    ->useAttributeAsKey('name')
                    ->scalarPrototype()
                ->end()
            ->end()
        ;
        return $treeBuilder;
    }
}
