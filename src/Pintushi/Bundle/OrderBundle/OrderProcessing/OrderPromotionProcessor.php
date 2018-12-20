<?php

namespace Pintushi\Bundle\OrderBundle\OrderProcessing;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Component\Order\Processor\OrderProcessorInterface;
use Pintushi\Bundle\PromotionBundle\Processor\PromotionProcessorInterface;
use Webmozart\Assert\Assert;

final class OrderPromotionProcessor implements OrderProcessorInterface
{
    /**
     * @var PromotionProcessorInterface
     */
    private $promotionProcessor;

    public function __construct(PromotionProcessorInterface $promotionProcessor)
    {
        $this->promotionProcessor = $promotionProcessor;
    }

    /**
     * {@inheritdoc}
     */
    public function process(OrderInterface $order): void
    {
        $this->promotionProcessor->process($order);
    }
}
