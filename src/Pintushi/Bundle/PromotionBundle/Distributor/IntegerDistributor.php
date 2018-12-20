<?php

namespace Pintushi\Bundle\PromotionBundle\Distributor;

class IntegerDistributor implements IntegerDistributorInterface
{
    /**
     * {@inheritdoc}
     */
    public function distribute(float $amount, int $numberOfTargets): array
    {
        if (!$this->validateNumberOfTargets($numberOfTargets)) {
            throw new \InvalidArgumentException('Number of targets must be an integer, bigger than 0.');
        }

        $sign = $amount < 0 ? -1 : 1;
        $amount = abs($amount);

        $low = (int) ($amount / $numberOfTargets);
        $high = $low + 1;

        $remainder = $amount % $numberOfTargets;
        $result = [];

        for ($i = 0; $i < $remainder; ++$i) {
            $result[] = $high * $sign;
        }

        for ($i = $remainder; $i < $numberOfTargets; ++$i) {
            $result[] = $low * $sign;
        }

        return $result;
    }

    private function validateNumberOfTargets(int $numberOfTargets): bool
    {
        return is_int($numberOfTargets) && 1 <= $numberOfTargets;
    }
}
