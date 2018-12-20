<?php

namespace Pintushi\Component\Core\Factory;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionRuleInterface;

interface PromotionRuleFactoryInterface
{

    public function createItemTotal(string $channelId, int $amount): PromotionRuleInterface;


    public function createNthOrder(int $nth): PromotionRuleInterface;
}
