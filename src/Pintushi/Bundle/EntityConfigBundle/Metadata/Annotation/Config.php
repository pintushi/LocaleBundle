<?php

namespace Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation;

use Doctrine\Common\Annotations\Annotation;

use Pintushi\Bundle\EntityConfigBundle\Entity\ConfigModel;
use Pintushi\Bundle\EntityConfigBundle\Exception\AnnotationException;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Config
{
    /** @var string */
    public $mode = ConfigModel::MODE_DEFAULT;

    /** @var array */
    public $defaultValues = array();

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (isset($data['mode'])) {
            $this->mode = $data['mode'];
        } elseif (isset($data['value'])) {
            $this->mode = $data['value'];
        }
        if (isset($data['defaultValues'])) {
            $this->defaultValues = $data['defaultValues'];
        }

        if (!is_array($this->defaultValues)) {
            throw new AnnotationException(
                sprintf(
                    'Annotation "Config" parameter "defaultValues" expect "array" but "%s" given',
                    gettype($this->defaultValues)
                )
            );
        }

        $availableMode = array(
            ConfigModel::MODE_DEFAULT,
            ConfigModel::MODE_HIDDEN,
            ConfigModel::MODE_READONLY
        );

        if (!in_array($this->mode, $availableMode, true)) {
            throw new AnnotationException(
                sprintf('Annotation "Config" give invalid parameter "mode" : "%s"', $this->mode)
            );
        }
    }
}
