<?php

namespace Pintushi\Bundle\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class PhoneNumberConstraint extends Constraint
{
    public $message = 'This value is not a valid phone number.';
}
