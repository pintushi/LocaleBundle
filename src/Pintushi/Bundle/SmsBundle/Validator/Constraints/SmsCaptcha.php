<?php

declare(strict_types=1);

namespace Pintushi\Bundle\SmsBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

final class SmsCaptcha extends Constraint
{
    public $codeField;
    public $phoneNumberField;

    public function getRequiredOptions()
    {
        return array('codeField', 'phoneNumberField');
    }

    /**
     * @var string
     */
    public $message = '验证码错误';

     /**
     * @inheritdoc
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
