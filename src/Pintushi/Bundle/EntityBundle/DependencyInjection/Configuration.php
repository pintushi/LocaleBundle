<?php

namespace Pintushi\Bundle\EntityBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class Configuration implements ConfigurationInterface
{
    const ROOT_NODE = 'pintushi_entity';

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root(self::ROOT_NODE);

        $rootNode
            ->children()
                ->scalarNode('cache_provider')->defaultValue('array')->end()
                ->arrayNode('entity_aliases')
                    ->info('Entity aliases')
                    ->example(
                        [
                            'Acme\Bundle\Entity\SomeEntity' => [
                                'alias' => 'someentity',
                                'plural_alias' => 'someentities'
                            ]
                        ]
                    )
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('alias')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->validate()
                                    ->ifTrue(
                                        function ($v) {
                                            return !preg_match('/^[a-z][a-z0-9_]*$/D', $v);
                                        }
                                    )
                                    ->thenInvalid(
                                        'The value %s cannot be used as an entity alias '
                                        . 'because it contains illegal characters. '
                                        . 'The valid alias should start with a letter and only contain '
                                        . 'lower case letters, numbers and underscores ("_").'
                                    )
                                ->end()
                            ->end()
                            ->scalarNode('plural_alias')
                                ->isRequired()
                                ->cannotBeEmpty()
                                ->validate()
                                    ->ifTrue(
                                        function ($v) {
                                            return !preg_match('/^[a-z][a-z0-9_]*$/D', $v);
                                        }
                                    )
                                    ->thenInvalid(
                                        'The value %s cannot be used as an entity plural alias '
                                        . 'because it contains illegal characters. '
                                        . 'The valid alias should start with a letter and only contain '
                                        . 'lower case letters, numbers and underscores ("_").'
                                    )
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('entity_alias_exclusions')
                    ->info('Entities which has no aliases')
                    ->example(
                        [
                            'Acme\Bundle\Entity\SomeEntity1',
                            'Acme\Bundle\Entity\SomeEntity2'
                        ]
                    )
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('entity_name_formats')
                    ->info('Formats of entity text representation')
                    ->example(
                        [
                            'long' => [
                                'fallback' => 'short'
                            ],
                            'short' => null,
                            'html' => [
                                'fallback'  => 'long',
                                'decorator' => true
                            ]
                        ]
                    )
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('fallback')->defaultValue(null)->end()
                        ->end()
                    ->end()
                    ->validate()
                        ->always(
                            function ($v) {
                                $known        = array_fill_keys(array_keys($v), true);
                                $dependencies = [];
                                foreach ($v as $name => $item) {
                                    if (empty($item['fallback'])) {
                                        continue;
                                    }
                                    $fallback = $item['fallback'];
                                    if (!isset($known[$fallback])) {
                                        throw new InvalidConfigurationException(
                                            sprintf(
                                                'The undefined text representation format "%s" cannot be used as '
                                                . 'a fallback format for the format "%s".',
                                                $fallback,
                                                $name
                                            )
                                        );
                                    }
                                    if ($name === $fallback) {
                                        throw new InvalidConfigurationException(
                                            sprintf(
                                                'The text representation format "%s" have '
                                                . 'a cyclic dependency on itself.',
                                                $name
                                            )
                                        );
                                    }
                                    $dependencies[$name] = [$fallback];
                                }
                                $continue = true;
                                while ($continue) {
                                    $continue = false;
                                    foreach ($v as $name => $item) {
                                        if (empty($item['fallback'])) {
                                            continue;
                                        }
                                        $fallback = $item['fallback'];
                                        foreach ($dependencies as $depName => &$depItems) {
                                            if ($depName === $name) {
                                                continue;
                                            }
                                            if (in_array($name, $depItems, true)) {
                                                if (in_array($fallback, $depItems, true)) {
                                                    continue;
                                                }
                                                $depItems[] = $fallback;
                                                if ($fallback === $depName) {
                                                    throw new InvalidConfigurationException(
                                                        sprintf(
                                                            'The text representation format "%s" have '
                                                            . 'a cyclic dependency "%s".',
                                                            $depName,
                                                            implode(' -> ', $depItems)
                                                        )
                                                    );
                                                }
                                                $continue   = true;
                                            }
                                        }
                                    }
                                }

                                return $v;
                            }
                        )
                ->end()
            ->end();

        return $treeBuilder;
    }
}
