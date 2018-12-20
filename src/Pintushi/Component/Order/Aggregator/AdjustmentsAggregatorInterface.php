<?php

namespace Pintushi\Component\Order\Aggregator;

use Pintushi\Bundle\OrderBundle\Entity\AdjustmentInterface;

interface AdjustmentsAggregatorInterface
{
    /**
     * @param AdjustmentInterface[] $adjustments
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function aggregate(array $adjustments): array;
}
