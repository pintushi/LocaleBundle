<?php

namespace Pintushi\Bundle\SmsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Overtrue\EasySms\Strategies\OrderStrategy;

/**
 * @author Vidy Videni<videni@foxmail.com>
 */
class Configuration implements ConfigurationInterface
{
    private $supportGateways = [
        'aliyun',
        'rongcloud',
    ];
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pintushi_sms');

        $rootNode
            ->children()
                ->scalarNode('timeout')->defaultValue(5)->end()
                ->arrayNode('enabled_gateways')
                    ->prototype('scalar')
                        ->cannotBeEmpty()
                        ->isRequired()
                        ->validate()
                            ->ifNotInArray($this->supportGateways)
                            ->thenInvalid('The SMS %s gateway is not supported')
                        ->end()
                    ->end()
                ->end()
                ->append($this->getGatewayConfigsNode())
                ->append($this->getTemplatesNode())
            ->end()
        ->end();

        return $treeBuilder;
    }

    private function getGatewayConfigsNode()
    {
        $builder    = new TreeBuilder();
        $node       = $builder->root('gateways');

        $node
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->prototype('scalar')->end()
            ->end()
        ;

        return $node;
    }

    private function getTemplatesNode()
    {
        $builder    = new TreeBuilder();
        $node       = $builder->root('templates');
        $node
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->prototype('scalar')->end()
            ->end()
        ;

        return $node;
    }
}
