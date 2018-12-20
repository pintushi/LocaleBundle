<?php

namespace Pintushi\Bundle\AddressBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @author Vidy Videni <foxmail.com>
 */
class AddressConstraint extends Constraint
{
    /**
     * @var string
     */
    public $message = 'pintushi.address.valid';

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
