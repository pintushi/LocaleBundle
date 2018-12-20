<?php

namespace Pintushi\Bundle\EntityConfigBundle\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\DriverInterface;

use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Pintushi\Bundle\EntityConfigBundle\Metadata\EntityMetadata;
use Pintushi\Bundle\EntityConfigBundle\Metadata\FieldMetadata;

class AnnotationDriver implements DriverInterface
{
    /**
     * Annotation reader uses a full class pass for parsing
     */
    const ENTITY_CONFIG = Config::class;
    const FIELD_CONFIG  = ConfigField::class;

    /**
     * @var Reader
     */
    protected $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        /** @var Config $annotation */
        if ($annotation = $this->reader->getClassAnnotation($class, self::ENTITY_CONFIG)) {
            $metadata = new EntityMetadata($class->getName());

            $metadata->configurable  = true;
            $metadata->defaultValues = $annotation->defaultValues;
            $metadata->mode          = $annotation->mode;

            foreach ($class->getProperties() as $property) {
                $propertyMetadata = new FieldMetadata($class->getName(), $property->getName());

                /** @var ConfigField $annotation */
                if ($annotation = $this->reader->getPropertyAnnotation($property, self::FIELD_CONFIG)) {
                    $propertyMetadata->defaultValues = $annotation->defaultValues;
                    $propertyMetadata->mode          = $annotation->mode;
                }

                $metadata->addPropertyMetadata($propertyMetadata);
            }

            return $metadata;
        }

        return null;
    }
}
