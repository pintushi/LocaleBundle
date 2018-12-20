<?php

namespace Pintushi\Bundle\TaxonomyBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Pintushi\Bundle\TaxonomyBundle\Repository\TaxonRepositoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ParentTaxonValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }
        $parent = $value->getParent();

        if ($parent && $id = $value->getId() && $parent->getId() === $id) {
            $this->context->addViolation($constraint->message);

            return;
        }
    }
}
