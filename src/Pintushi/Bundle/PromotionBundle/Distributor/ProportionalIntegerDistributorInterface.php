<?php

namespace Pintushi\Bundle\PromotionBundle\Distributor;

interface ProportionalIntegerDistributorInterface
{
    /**
     * 将优惠按比例分配给每个OrderItem, 因为价格为整数，所以此处不是均分.
     *
     * @param array $integers
     * @param int $amount
     *
     * @return array
     */
    public function distribute(array $integers, int $amount): array;
}
