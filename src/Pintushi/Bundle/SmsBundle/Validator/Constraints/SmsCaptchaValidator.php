<?php

declare(strict_types=1);

namespace Pintushi\Bundle\SmsBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Pintushi\Bundle\SmsBundle\Verification\SmsCaptchaValidator as PintushiSmsCaptchaValidator;

final class SmsCaptchaValidator extends ConstraintValidator
{
    /**
     * @var OrderRepositoryInterface
     */
    private $smsCaptchaValidator;

    private static $propertyAccessor;

    public function __construct(PintushiSmsCaptchaValidator $smsCaptchaValidator)
    {
        $this->smsCaptchaValidator = $smsCaptchaValidator;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        return ;

        if ($value === null) {
            return;
        }
        $propertyAccessor = $this->getPropertyAccessor();

        $code = $propertyAccessor->getValue($value, $constraint->codeField);
        $phoneNumber = $propertyAccessor->getValue($value, $constraint->phoneNumberField);

        if (!$this->smsCaptchaValidator->validate($phoneNumber, $code)) {
            $this
            ->context
            ->buildViolation($constraint->message)
            ->atPath('code')
            ->addViolation();
        }
    }

     /**
     * @return PropertyAccessor
     */
    private static function getPropertyAccessor()
    {
        if (!self::$propertyAccessor) {
            self::$propertyAccessor = PropertyAccess::createPropertyAccessor();
        }

        return self::$propertyAccessor;
    }
}
