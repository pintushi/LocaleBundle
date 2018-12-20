<?php

namespace Pintushi\Bundle\EntityConfigBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use Pintushi\Bundle\EntityConfigBundle\DependencyInjection\Compiler\AttributeBlockTypeMapperPass;
use Pintushi\Bundle\EntityConfigBundle\DependencyInjection\Compiler\ServiceMethodPass;
use Pintushi\Bundle\EntityConfigBundle\DependencyInjection\Compiler\EntityConfigPass;

class PintushiEntityConfigBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new EntityConfigPass);
    }
}
