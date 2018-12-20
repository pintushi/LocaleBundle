<?php


declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Validator;

use Pintushi\Bundle\PromotionBundle\Validator\Constraints\PromotionSubjectCoupon;
use Pintushi\Bundle\PromotionBundle\Checker\Eligibility\PromotionEligibilityCheckerInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionCouponAwarePromotionSubjectInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

final class PromotionSubjectCouponValidator extends ConstraintValidator
{
    /**
     * @var PromotionEligibilityCheckerInterface
     */
    private $promotionEligibilityChecker;

    /**
     * @param PromotionEligibilityCheckerInterface $promotionEligibilityChecker
     */
    public function __construct(PromotionEligibilityCheckerInterface $promotionEligibilityChecker)
    {
        $this->promotionEligibilityChecker = $promotionEligibilityChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        /** @var PromotionSubjectCoupon $constraint */
        Assert::isInstanceOf($constraint, PromotionSubjectCoupon::class);

        if (!$value instanceof PromotionCouponAwarePromotionSubjectInterface) {
            return;
        }

        $promotionCoupon = $value->getPromotionCoupon();
        if ($promotionCoupon === null) {
            return;
        }

        if ($this->promotionEligibilityChecker->isEligible($value, $promotionCoupon->getPromotion())) {
            return;
        }

        $this->context->buildViolation($constraint->message)->atPath('promotionCoupon')->addViolation();
    }
}
