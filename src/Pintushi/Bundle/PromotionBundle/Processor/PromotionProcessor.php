<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Processor;

use Pintushi\Bundle\PromotionBundle\Action\PromotionApplicatorInterface;
use Pintushi\Bundle\PromotionBundle\Checker\Eligibility\PromotionEligibilityCheckerInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;
use Pintushi\Bundle\PromotionBundle\Provider\PreQualifiedPromotionsProviderInterface;

final class PromotionProcessor implements PromotionProcessorInterface
{
    /**
     * @var PreQualifiedPromotionsProviderInterface
     */
    private $preQualifiedPromotionsProvider;

    /**
     * @var PromotionEligibilityCheckerInterface
     */
    private $promotionEligibilityChecker;

    /**
     * @var PromotionApplicatorInterface
     */
    private $promotionApplicator;

    /**
     * @param PreQualifiedPromotionsProviderInterface $preQualifiedPromotionsProvider
     * @param PromotionEligibilityCheckerInterface $promotionEligibilityChecker
     * @param PromotionApplicatorInterface $promotionApplicator
     */
    public function __construct(
        PreQualifiedPromotionsProviderInterface $preQualifiedPromotionsProvider,
        PromotionEligibilityCheckerInterface $promotionEligibilityChecker,
        PromotionApplicatorInterface $promotionApplicator
    ) {
        $this->preQualifiedPromotionsProvider = $preQualifiedPromotionsProvider;
        $this->promotionEligibilityChecker = $promotionEligibilityChecker;
        $this->promotionApplicator = $promotionApplicator;
    }

    /**
     * {@inheritdoc}
     */
    public function process(PromotionSubjectInterface $subject): void
    {
        foreach ($subject->getPromotions() as $promotion) {
            $this->promotionApplicator->revert($subject, $promotion);
        }

        $preQualifiedPromotions = $this->preQualifiedPromotionsProvider->getPromotions($subject);

        foreach ($preQualifiedPromotions as $promotion) {
            if ($promotion->isExclusive() && $this->promotionEligibilityChecker->isEligible($subject, $promotion)) {
                $this->promotionApplicator->apply($subject, $promotion);

                return;
            }
        }

        foreach ($preQualifiedPromotions as $promotion) {
            if (!$promotion->isExclusive() && $this->promotionEligibilityChecker->isEligible($subject, $promotion)) {
                $this->promotionApplicator->apply($subject, $promotion);
            }
        }
    }
}
