<?php


namespace Pintushi\Component\Order\Processor;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;

interface OrderProcessorInterface
{
    public function process(OrderInterface $order): void;
}
