<?php

namespace Pintushi\Bundle\PromotionBundle\Distributor;

interface IntegerDistributorInterface
{
    /**
     * @param float $amount
     * @param int $numberOfTargets
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function distribute(float $amount, int $numberOfTargets): array;
}
