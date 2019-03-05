<?php

namespace Pintushi\Component\EntitySerializer;

use Metadata\MetadataFactoryInterface;

class MetadataFactory extends  MetadataFactoryInterface
{
    private $entityConfig;

    public function __construct($entityConfigName, EntityConfigLoader $entityConfigLoader)
    {
        $this->entityConfig = $this->entityConfigLoader->load($entityConfigName);
    }

    public function getMetadataForClass($className)
    {
        $this->entityConfig->getFields();


    }
}
