<?php

declare(strict_types=1);

namespace Pintushi\Bundle\InventoryBundle\Validator\Constraints;

use Pintushi\Bundle\InventoryBundle\Checker\AvailabilityCheckerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

final class InStockValidator extends ConstraintValidator
{
    /**
     * @var AvailabilityCheckerInterface
     */
    private $availabilityChecker;

    /**
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * @param AvailabilityCheckerInterface $availabilityChecker
     */
    public function __construct(AvailabilityCheckerInterface $availabilityChecker)
    {
        $this->availabilityChecker = $availabilityChecker;
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        /** @var InStock $constraint */
        Assert::isInstanceOf($constraint, InStock::class);

        $stockable = $this->accessor->getValue($value, $constraint->stockablePath);
        if (null === $stockable) {
            return;
        }

        $quantity = $this->accessor->getValue($value, $constraint->quantityPath);
        if (null === $quantity) {
            return;
        }

        if (!$this->availabilityChecker->isStockSufficient($stockable, $quantity)) {
            $this->context->addViolation(
                $constraint->message,
                ['%itemName%' => $stockable->getInventoryName()]
            );
        }
    }
}
