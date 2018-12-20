<?php

declare(strict_types=1);

namespace Pintushi\Bundle\ReviewBundle\DependencyInjection;

use Pintushi\Bundle\ReviewBundle\Entity\ReviewerInterface;
use Pintushi\Bundle\ReviewBundle\Entity\ReviewInterface;
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
        $rootNode = $treeBuilder->root('pintushi_review');

        return $treeBuilder;
    }
}
