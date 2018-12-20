<?php

namespace Pintushi\Bundle\PromotionBundle\Distributor;

use Webmozart\Assert\Assert;

final class ProportionalIntegerDistributor implements ProportionalIntegerDistributorInterface
{
    /**
     * {@inheritdoc}
     */
    public function distribute(array $integers, int $amount): array
    {
        Assert::allInteger($integers);
        Assert::integer($amount);

        $total = array_sum($integers);
        $distributedAmounts = [];

        foreach ($integers as $element) {
            //遇到.5的情况时，向下舍入number到precision小数位.如舍入1.5到1,舍入-1.5到 -1.
            $distributedAmounts[] = (int) round(($element * $amount) / $total, 0, PHP_ROUND_HALF_DOWN);
        }

        $missingAmount = $amount - array_sum($distributedAmounts);
        for ($i = 0; $i < abs($missingAmount); $i++) {
            $distributedAmounts[$i] += $missingAmount >= 0 ? 1 : -1;
        }

        return $distributedAmounts;
    }
}
