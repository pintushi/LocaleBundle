<?php

namespace Pintushi\Bundle\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EntityOwnedByOrganization extends Constraint
{
    public $message = "You don't have access to entity {{id}} of {{entity}}";

    public $fields = array();

    public function getRequiredOptions()
    {
        return array('fields');
    }

    /**
     * @inheritdoc
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
