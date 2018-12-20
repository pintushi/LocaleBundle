<?php


namespace Pintushi\Bundle\OrderBundle\StateResolver;

use SM\Factory\FactoryInterface;
use SM\StateMachine\StateMachineInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Component\Order\StateResolver\StateResolverInterface;
use Pintushi\Bundle\PaymentBundle\Entity\PaymentInterface;
use Pintushi\Bundle\OrderBundle\OrderPaymentTransitions;

final class OrderPaymentStateResolver implements StateResolverInterface
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
        $stateMachine = $this->stateMachineFactory->get($order, OrderPaymentTransitions::GRAPH);
        $targetTransition = $this->getTargetTransition($order);

        if (null !== $targetTransition) {
            $this->applyTransition($stateMachine, $targetTransition);
        }
    }

    private function applyTransition(StateMachineInterface $stateMachine, string $transition): void
    {
        if ($stateMachine->can($transition)) {
            $stateMachine->apply($transition);
        }
    }

    private function getTargetTransition(OrderInterface $order): ?string
    {
        $payment = $order->getPayment();

        if (PaymentInterface::STATE_REFUNDED === $payment->getState()) {
            return OrderPaymentTransitions::TRANSITION_REFUND;
        }

        if (PaymentInterface::STATE_COMPLETED === $payment->getState()) {
            return OrderPaymentTransitions::TRANSITION_PAY;
        }

        return null;
    }
}
