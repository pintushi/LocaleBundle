<?php

namespace Pintushi\Bundle\EntityBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Oro\Component\DoctrineUtils\ORM\QueryHintResolver;

class QueryHintResolverPass implements CompilerPassInterface
{
    const TAG_NAME = 'pintushi_security.query_hint';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(QueryHintResolver::class)) {
            return;
        }

        $resolverDef = $container->getDefinition(QueryHintResolver::class);
        foreach ($container->findTaggedServiceIds(self::TAG_NAME) as $attributes) {
            foreach ($attributes as $attr) {
                if (isset($attr['tree_walker'])) {
                    $resolverDef->addMethodCall(
                        'addTreeWalker',
                        [
                            $attr['hint'],
                            $attr['tree_walker'],
                            isset($attr['walker_hint_provider']) ? new Reference($attr['walker_hint_provider']) : null,
                            isset($attr['alias']) ? $attr['alias'] : null
                        ]
                    );
                } elseif (isset($attr['output_walker'])) {
                    $resolverDef->addMethodCall(
                        'addOutputWalker',
                        [
                            $attr['hint'],
                            $attr['output_walker'],
                            isset($attr['walker_hint_provider']) ? new Reference($attr['walker_hint_provider']) : null,
                            isset($attr['alias']) ? $attr['alias'] : null
                        ]
                    );
                }
            }
        }
    }
}
