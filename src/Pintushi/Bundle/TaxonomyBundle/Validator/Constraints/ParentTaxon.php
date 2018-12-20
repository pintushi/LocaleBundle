<?php

namespace Pintushi\Bundle\TaxonomyBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ParentTaxon extends Constraint
{
    public $message = "Taxonomy can't set self as Parent.";

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'parent_taxonomy_validator';
    }

    /**
     * @inheritdoc
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
