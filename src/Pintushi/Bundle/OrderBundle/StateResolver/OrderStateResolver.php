<?php

namespace Pintushi\Bundle\OrderBundle\StateResolver;

use SM\Factory\FactoryInterface;
use Pintushi\Bundle\OrderBundle\OrderPaymentStates;
use Pintushi\Bundle\OrderBundle\OrderShippingStates;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Component\Order\OrderTransitions;
use Pintushi\Component\Order\StateResolver\StateResolverInterface;

final class OrderStateResolver implements StateResolverInterface
{
    /**
     * @var FactoryInterface
     */
    private $stateMachineFactory;

    public function __construct(FactoryInterface $stateMachineFactory)
    {
        $this->stateMachineFactory = $stateMachineFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(OrderInterface $order): void
    {
        $stateMachine = $this->stateMachineFactory->get($order, OrderTransitions::GRAPH);

        if ($this->canOrderBeFulfilled($order) && $stateMachine->can(OrderTransitions::TRANSITION_FULFILL)) {
            $stateMachine->apply(OrderTransitions::TRANSITION_FULFILL);
        }
    }

    private function canOrderBeFulfilled(OrderInterface $order): bool
    {
        return
            OrderPaymentStates::STATE_PAID === $order->getPaymentState() &&
            OrderShippingStates::STATE_SHIPPED === $order->getShippingState()
        ;
    }
}
