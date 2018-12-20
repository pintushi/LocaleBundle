<?php

namespace Pintushi\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

use Pintushi\Bundle\CoreBundle\Metadata\CachedPropertyNameCollectionFactory;
use Pintushi\Bundle\CoreBundle\ORM\Extension\PaginationExtension;
use Pintushi\Bundle\CoreBundle\ORM\CollectionDataProvider;
use Pintushi\Bundle\CoreBundle\Serializer\Hal\ApiRelationItemNormalizer;
use Pintushi\Bundle\CoreBundle\Serializer\Hal\ApiRelationCollectionNormalizer;
use Pintushi\Bundle\CoreBundle\EventListener\DeserializeListener;
use Pintushi\Bundle\CoreBundle\EventListener\WriteListener;

class AppPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $container
            ->getDefinition('api_platform.doctrine.orm.default.collection_data_provider')
            ->setClass(CollectionDataProvider::class)
            ->addArgument(new Reference('pintushi.acl_helper'))
            ->addArgument(new Reference('api_platform.metadata.resource.metadata_factory'))
            ;

        $container
            ->getDefinition('api_platform.doctrine.orm.query_extension.pagination')
            ->setClass(PaginationExtension::class)
            ->addArgument(new Reference('pintushi.acl_helper'))
            ;

        $container
            ->getDefinition('api_platform.security.expression_language')
            ->addMethodCall('registerProvider', [new Reference('pintushi.expression_language.workflow_provider')])
        ;
        $container
            ->getDefinition('api_platform.listener.request.deserialize')
            ->setClass(DeserializeListener::class)
            ->addArgument(new Reference('pintushi_organization.entity_ownership.record_owner_data'))
        ;
        $container
            ->getDefinition('api_platform.listener.view.write')
            ->setClass(WriteListener::class)
            ->addArgument(new Reference('tactician.commandbus'))
        ;

        $container->getDefinition('sm.callback.cascade_transition')->setPublic(true);
    }
}
