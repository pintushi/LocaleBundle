<?php

namespace Pintushi\Bundle\EntityConfigBundle\Metadata;

use Metadata\MergeableClassMetadata;
use Metadata\MergeableInterface;

class EntityMetadata extends MergeableClassMetadata
{
    /**
     * @var bool
     */
    public $configurable = false;

    /**
     * @var string
     */
    public $mode;

    /**
     * @var array
     */
    public $defaultValues;

    /**
     * {@inheritdoc}
     */
    public function merge(MergeableInterface $object)
    {
        parent::merge($object);

        if ($object instanceof EntityMetadata) {
            $this->configurable  = $object->configurable;
            $this->defaultValues = $object->defaultValues;
            $this->mode          = $object->mode;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(
            array(
                $this->configurable,
                $this->defaultValues,
                $this->mode,
                parent::serialize(),
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str)
    {
        list(
            $this->configurable,
            $this->defaultValues,
            $this->mode,
            $parentStr
            ) = unserialize($str);

        parent::unserialize($parentStr);
    }
}
