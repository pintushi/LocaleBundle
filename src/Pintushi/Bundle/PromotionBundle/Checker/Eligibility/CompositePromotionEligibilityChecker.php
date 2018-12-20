<?php


declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Checker\Eligibility;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;
use Webmozart\Assert\Assert;

final class CompositePromotionEligibilityChecker implements PromotionEligibilityCheckerInterface
{
    /**
     * @var PromotionEligibilityCheckerInterface[]
     */
    private $promotionEligibilityCheckers;

    /**
     * @param PromotionEligibilityCheckerInterface[] $promotionEligibilityCheckers
     */
    public function __construct(array $promotionEligibilityCheckers)
    {
        Assert::notEmpty($promotionEligibilityCheckers);
        Assert::allIsInstanceOf($promotionEligibilityCheckers, PromotionEligibilityCheckerInterface::class);

        $this->promotionEligibilityCheckers = $promotionEligibilityCheckers;
    }

    /**
     * {@inheritdoc}
     */
    public function isEligible(PromotionSubjectInterface $promotionSubject, PromotionInterface $promotion): bool
    {
        foreach ($this->promotionEligibilityCheckers as $promotionEligibilityChecker) {
            if (!$promotionEligibilityChecker->isEligible($promotionSubject, $promotion)) {
                return false;
            }
        }

        return true;
    }
}
