<?php

namespace Pintushi\Bundle\PromotionBundle\Action;

use Pintushi\Bundle\PromotionBundle\Distributor\ProportionalIntegerDistributorInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\PromotionBundle\Applicator\OrderItemsPromotionAdjustmentsApplicatorInterface;
use Pintushi\Bundle\PromotionBundle\Action\PromotionActionCommandInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;

final class PercentageDiscountPromotionActionCommand extends DiscountPromotionActionCommand implements PromotionActionCommandInterface
{
    const TYPE = 'order_percentage_discount';

    /**
     * @var ProportionalIntegerDistributorInterface
     */
    private $distributor;

    /**
     * @var OrderItemsPromotionAdjustmentsApplicatorInterface
     */
    private $orderItemsPromotionAdjustmentsApplicator;

    public function __construct(
        ProportionalIntegerDistributorInterface $distributor,
        OrderItemsPromotionAdjustmentsApplicatorInterface $orderItemsPromotionAdjustmentsApplicator
    ) {
        $this->distributor = $distributor;
        $this->orderItemsPromotionAdjustmentsApplicator = $orderItemsPromotionAdjustmentsApplicator;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(PromotionSubjectInterface $subject, array $configuration, PromotionInterface $promotion): bool
    {
        /** @var OrderInterface $subject */
        if (!$this->isSubjectValid($subject)) {
            return false;
        }

        try {
            $this->isConfigurationValid($configuration);
        } catch (\InvalidArgumentException $exception) {
            return false;
        }

        $promotionAmount = $this->calculateAdjustmentAmount($subject->getPromotionSubjectTotal(), $configuration['percentage']);
        if (0 === $promotionAmount) {
            return false;
        }

        $itemsTotal = [];
        foreach ($subject->getItems() as $orderItem) {
            $itemsTotal[] = $orderItem->getTotal();
        }

        $splitPromotion = $this->distributor->distribute($itemsTotal, $promotionAmount);
        $this->orderItemsPromotionAdjustmentsApplicator->apply($subject, $promotion, $splitPromotion);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function isConfigurationValid(array $configuration): void
    {
        if (!isset($configuration['percentage']) || !is_float($configuration['percentage'])) {
            throw new \InvalidArgumentException('"percentage" must be set and must be a float.');
        }
    }

    private function calculateAdjustmentAmount(int $promotionSubjectTotal, int $percentage): int
    {
        return -1 * (int) round($promotionSubjectTotal * $percentage);
    }
}
