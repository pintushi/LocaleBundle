<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Validator;

use Pintushi\Bundle\PromotionBundle\Validator\Constraints\CouponPossibleGenerationAmount;
use Pintushi\Bundle\PromotionBundle\Generator\GenerationPolicyInterface;
use Pintushi\Bundle\PromotionBundle\Generator\PromotionCouponGeneratorInstructionInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

/**
 * @author Arkadiusz Krakowiak <arkadiusz.krakowiak@lakion.com>
 */
final class CouponGenerationAmountValidator extends ConstraintValidator
{
    /**
     * @var GenerationPolicyInterface
     */
    private $generationPolicy;

    /**
     * @param GenerationPolicyInterface $generationPolicy
     */
    public function __construct(GenerationPolicyInterface $generationPolicy)
    {
        $this->generationPolicy = $generationPolicy;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($instruction, Constraint $constraint): void
    {
        if (null === $instruction->getCodeLength() || null === $instruction->getAmount()) {
            return;
        }

        /** @var PromotionCouponGeneratorInstructionInterface $value */
        Assert::isInstanceOf($instruction, PromotionCouponGeneratorInstructionInterface::class);

        /** @var CouponPossibleGenerationAmount $constraint */
        Assert::isInstanceOf($constraint, CouponPossibleGenerationAmount::class);

        if (!$this->generationPolicy->isGenerationPossible($instruction)) {
            $this->context->addViolation(
                $constraint->message,
                [
                    '%expectedAmount%' => $instruction->getAmount(),
                    '%codeLength%' => $instruction->getCodeLength(),
                    '%possibleAmount%' => $this->generationPolicy->getPossibleGenerationAmount($instruction),
                ]
            );
        }
    }
}
