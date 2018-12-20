<?php

namespace Pintushi\Component\Order\Processor;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Zend\Stdlib\PriorityQueue;

final class CompositeOrderProcessor implements OrderProcessorInterface
{
    /**
     * @var PriorityQueue|OrderProcessorInterface[]
     */
    private $orderProcessors;

    public function __construct()
    {
        $this->orderProcessors = new PriorityQueue();
    }

    public function addProcessor(OrderProcessorInterface $orderProcessor, int $priority = 0): void
    {
        $this->orderProcessors->insert($orderProcessor, $priority);
    }

    /**
     * {@inheritdoc}
     */
    public function process(OrderInterface $order): void
    {
        foreach ($this->orderProcessors as $orderProcessor) {
            $orderProcessor->process($order);
        }
    }
}
