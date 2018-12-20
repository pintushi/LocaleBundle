<?php

namespace Pintushi\Bundle\TaxonomyBundle\DependencyInjection;

use Pintushi\Bundle\TaxonomyBundle\Form\Type\TaxonTranslationType;
use Pintushi\Bundle\TaxonomyBundle\Form\Type\TaxonType;
use Pintushi\Component\Taxonomy\Model\Taxon;
use Pintushi\Component\Taxonomy\Model\TaxonInterface;
use Pintushi\Component\Taxonomy\Model\TaxonTranslation;
use Pintushi\Component\Taxonomy\Model\TaxonTranslationInterface;
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
        $rootNode = $treeBuilder->root('pintushi_taxonomy');

        return $treeBuilder;
    }
}
