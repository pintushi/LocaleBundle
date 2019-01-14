<?php

namespace Pintushi\Bundle\PromotionBundle\Action;

use Pintushi\Bundle\PromotionBundle\Distributor\ProportionalIntegerDistributorInterface;
use Pintushi\Bundle\PromotionBundle\Applicator\OrderItemsPromotionAdjustmentsApplicatorInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;
use Webmozart\Assert\Assert;

final class FixedDiscountPromotionActionCommand extends DiscountPromotionActionCommand
{
    const TYPE = 'order_fixed_discount';

    /**
     * @var ProportionalIntegerDistributorInterface
     */
    private $proportionalDistributor;

    /**
     * @var OrderItemsPromotionAdjustmentsApplicatorInterface
     */
    private $orderItemsPromotionAdjustmentsApplicator;

    public function __construct(
        ProportionalIntegerDistributorInterface $proportionalIntegerDistributor,
        OrderItemsPromotionAdjustmentsApplicatorInterface $orderItemsPromotionAdjustmentsApplicator
    ) {
        $this->proportionalDistributor = $proportionalIntegerDistributor;
        $this->orderItemsPromotionAdjustmentsApplicator = $orderItemsPromotionAdjustmentsApplicator;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(PromotionSubjectInterface $subject, array $configuration, PromotionInterface $promotion): bool
    {
        if (!$this->isSubjectValid($subject)) {
            return false;
        }

        try {
            $this->isConfigurationValid($configuration);
        } catch (\InvalidArgumentException $exception) {
            return false;
        }

        $promotionAmount = $this->calculateAdjustmentAmount(
            $subject->getPromotionSubjectTotal(),
            $configuration['amount']
        );

        if (0 === $promotionAmount) {
            return false;
        }

        $itemsTotals = [];
        foreach ($subject->getItems() as $item) {
            $itemsTotals[] = $item->getTotal();
        }

        $splitPromotion = $this->proportionalDistributor->distribute($itemsTotals, $promotionAmount);
        $this->orderItemsPromotionAdjustmentsApplicator->apply($subject, $promotion, $splitPromotion);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function isConfigurationValid(array $configuration): void
    {
        Assert::keyExists($configuration, 'amount');
        Assert::integer($configuration['amount']);
    }

    private function calculateAdjustmentAmount(int $promotionSubjectTotal, int $targetPromotionAmount): int
    {
        return -1 * min($promotionSubjectTotal, $targetPromotionAmount);
    }
}
