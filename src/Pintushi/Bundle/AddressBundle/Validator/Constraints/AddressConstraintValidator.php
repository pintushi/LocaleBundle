<?php

namespace Pintushi\Bundle\AddressBundle\Validator\Constraints;

use Pintushi\Bundle\LocationBundle\Repository\LocationRepository;
use Pintushi\Bundle\LocationBundle\Entity\Location;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AddressConstraintValidator extends ConstraintValidator
{
    /**
     * @var LocationRepository
     */
    private $locationRepository;

    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value == '') {
            return;
        }

        $propertyPath = $this->context->getPropertyPath();
        foreach (iterator_to_array($this->context->getViolations()) as $violation) {
            if (0 === strpos($violation->getPropertyPath(), $propertyPath)) {
                return;
            }
        }

        $region = $this->locationRepository->findOneBy(['code' => $value, 'level' => Location::LEVEL_REGION]);
        if (null == $region) {
            $this->context->addViolation($constraint->message);
        }
    }
}
