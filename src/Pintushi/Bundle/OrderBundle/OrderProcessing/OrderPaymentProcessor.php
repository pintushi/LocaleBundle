<?php

namespace Pintushi\Bundle\OrderBundle\OrderProcessing;

use Pintushi\Bundle\PaymentBundle\Repository\PaymentMethodRepositoryInterface;
use Pintushi\Component\Core\Model\PaymentInterface;
use Webmozart\Assert\Assert;
use Pintushi\Bundle\OrderBundle\Entity\AdjustmentInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Component\Order\Processor\OrderProcessorInterface;
use Pintushi\Bundle\PaymentBundle\Resolver\DefaultPaymentMethodResolverInterface;
use Pintushi\Bundle\PaymentBundle\Factory\PaymentFactoryInterface;

final class OrderPaymentProcessor implements OrderProcessorInterface
{
    /**
     * @var PaymentFactoryInterface
     */
    private $paymentFactory;

    /**
     * @var PaymentMethodRepositoryInterface
     */
    private $paymentMethodRepository;

    private $defaultPaymentMethodResolver;

    private $currencyCode;

    public function __construct(
        PaymentFactoryInterface $paymentFactory,
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        DefaultPaymentMethodResolverInterface $defaultPaymentMethodResolver,
        $currencyCode = 'CNY'
    ) {
        $this->paymentFactory = $paymentFactory;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->defaultPaymentMethodResolver = $defaultPaymentMethodResolver;
        $this->currencyCode = $currencyCode;
    }

    /**
     * {@inheritdoc}
     */
    public function process(OrderInterface $order): void
    {
        if (OrderInterface::STATE_CANCELLED === $order->getState()) {
            return;
        }

        $payment = $order->getPayment();
        if (null === $payment) {/** @var $payment PaymentInterface */
            $payment = $this->paymentFactory->createWithAmountAndCurrencyCode($order->getTotal(), $this->currencyCode);
            $payment->setMethod($this->defaultPaymentMethodResolver->getDefaultPaymentMethod($payment));
            $order->setPayment($payment);

            return;
        }

        $payment->setCurrencyCode($this->currencyCode);
        $payment->setAmount($order->getTotal());
    }
}
