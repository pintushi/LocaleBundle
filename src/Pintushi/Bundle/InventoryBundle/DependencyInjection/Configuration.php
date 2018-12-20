<?php

declare(strict_types=1);

namespace Pintushi\Bundle\InventoryBundle\DependencyInjection;

use Pintushi\Bundle\InventoryBundle\Entity\InventoryUnit;
use Pintushi\Bundle\InventoryBundle\Entity\InventoryUnitInterface;
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
        $rootNode = $treeBuilder->root('pintushi_inventory');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('checker')->defaultValue('pintushi.availability_checker.default')->cannotBeEmpty()->end()
            ->end()
        ;


        return $treeBuilder;
    }
}
