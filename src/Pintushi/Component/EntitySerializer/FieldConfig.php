<?php

namespace Pintushi\Component\EntitySerializer;

/**
 * Represents the configuration of an entity field.
 */
class FieldConfig
{
    /** @var bool|null */
    protected $exclude;

    /** @var array */
    protected $items = [];

    /** @var EntityConfig|null */
    private $targetEntity;

    /**
     * Gets a native PHP array representation of the field configuration.
     *
     * @param bool $excludeTargetEntity
     *
     * @return array
     */
    public function toArray($excludeTargetEntity = false)
    {
        $result = $this->items;
        if (true === $this->exclude) {
            $result[ConfigUtil::EXCLUDE] = $this->exclude;
        }
        if (!$excludeTargetEntity && null !== $this->targetEntity) {
            $result = \array_merge($result, $this->targetEntity->toArray());
        }

        return $result;
    }

    /**
     * Indicates whether the field does not have a configuration.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return
            null === $this->exclude
            && empty($this->items)
            && (null === $this->targetEntity || $this->targetEntity->isEmpty());
    }

    /**
     * Make a deep copy of object.
     */
    public function __clone()
    {
        $this->items = ConfigUtil::cloneItems($this->items);
        if (null !== $this->targetEntity) {
            $this->targetEntity = clone $this->targetEntity;
        }
    }

    /**
     * Gets the configuration of the target entity if the field represents an association with another entity.
     *
     * @return EntityConfig|null
     */
    public function getTargetEntity()
    {
        return $this->targetEntity;
    }

    /**
     * Sets the configuration of the target entity.
     * Use this method only if the field represents an association with another entity.
     *
     * @param EntityConfig|null $targetEntity
     *
     * @return EntityConfig|null
     */
    public function setTargetEntity($targetEntity = null)
    {
        $this->targetEntity = $targetEntity;

        return $this->targetEntity;
    }

    /**
     * Indicates whether the field should be excluded.
     *
     * @return bool
     */
    public function isExcluded()
    {
        if (null === $this->exclude) {
            return false;
        }

        return $this->exclude;
    }

    /**
     * Sets a flag indicates whether the field should be excluded.
     *
     * @param bool|null $exclude The exclude flag or NULL to remove this option
     */
    public function setExcluded($exclude = true)
    {
        $this->exclude = $exclude;
    }

    /**
     * Indicates whether the target entity should be collapsed.
     *
     * @return bool
     */
    public function isCollapsed()
    {
        if (!\array_key_exists(ConfigUtil::COLLAPSE, $this->items)) {
            return false;
        }

        return $this->items[ConfigUtil::COLLAPSE];
    }

    /**
     * Sets a flag indicates whether the target entity should be collapsed.
     *
     * @param bool $collapse
     */
    public function setCollapsed($collapse = true)
    {
        if ($collapse) {
            $this->items[ConfigUtil::COLLAPSE] = $collapse;
        } else {
            unset($this->items[ConfigUtil::COLLAPSE]);
        }
    }

    /**
     * Gets the path of the field value.
     *
     * @param string|null $defaultValue
     *
     * @return string|null
     */
    public function getPropertyPath($defaultValue = null)
    {
        if (empty($this->items[ConfigUtil::PROPERTY_PATH])) {
            return $defaultValue;
        }

        return $this->items[ConfigUtil::PROPERTY_PATH];
    }

    /**
     * Sets the path of the field value.
     *
     * @param string|null $propertyPath
     */
    public function setPropertyPath($propertyPath = null)
    {
        if ($propertyPath) {
            $this->items[ConfigUtil::PROPERTY_PATH] = $propertyPath;
        } else {
            unset($this->items[ConfigUtil::PROPERTY_PATH]);
        }
    }
}