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

        $container->getDefinition('sm.callback.cascade_transition')->setPublic(true);
    }
}
