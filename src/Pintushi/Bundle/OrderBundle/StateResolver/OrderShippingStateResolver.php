<?php


namespace Pintushi\Bundle\OrderBundle\StateResolver;

use SM\Factory\FactoryInterface;
use SM\StateMachine\StateMachineInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Component\Order\StateResolver\StateResolverInterface;
use Pintushi\Bundle\ShippingBundle\Entity\ShipmentInterface;
use Pintushi\Bundle\OrderBundle\OrderShippingStates;
use Pintushi\Bundle\OrderBundle\OrderShippingTransitions;

final class OrderShippingStateResolver implements StateResolverInterface
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
        if (OrderShippingStates::STATE_SHIPPED === $order->getShippingState()) {
            return;
        }
        /** @var StateMachineInterface $stateMachine */
        $stateMachine = $this->stateMachineFactory->get($order, OrderShippingTransitions::GRAPH);

        $shipment= $order->getShipment();

        if ($shipment && $shipment->getState()=== ShipmentInterface::STATE_SHIPPED &&  OrderShippingStates::STATE_SHIPPED !== $order->getShippingState()) {
            $stateMachine->apply(OrderShippingTransitions::TRANSITION_SHIP);
        }
    }
}
