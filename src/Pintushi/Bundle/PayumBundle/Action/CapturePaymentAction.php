<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PayumBundle\Action;

use Payum\Core\Action\GatewayAwareAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Model\Payment as PayumPayment;
use Payum\Core\Request\Capture;
use Payum\Core\Request\Convert;
use Pintushi\Bundle\PayumBundle\Provider\PaymentDescriptionProviderInterface;
use Pintushi\Bundle\PayumBundle\Request\GetStatus;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\PaymentBundle\Entity\PaymentInterface;

final class CapturePaymentAction extends GatewayAwareAction
{
    /**
     * @var PaymentDescriptionProviderInterface
     */
    private $paymentDescriptionProvider;

    /**
     * @param PaymentDescriptionProviderInterface $paymentDescriptionProvider
     */
    public function __construct(PaymentDescriptionProviderInterface $paymentDescriptionProvider)
    {
        $this->paymentDescriptionProvider = $paymentDescriptionProvider;
    }

    /**
     * {@inheritdoc}
     *
     * @param Capture $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getModel();

        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        $this->gateway->execute($status = new GetStatus($payment));
        if ($status->isNew()) {
            try {
                $this->gateway->execute($convert = new Convert($payment, 'array', $request->getToken()));
                $payment->setDetails($convert->getResult());
            } catch (RequestNotSupportedException $e) {
                $totalAmount = $order->getTotal();
                $payumPayment = new PayumPayment();
                $payumPayment->setNumber($order->getNumber());
                $payumPayment->setTotalAmount($totalAmount);
                $payumPayment->setCurrencyCode($payment->getCurrencyCode());
                $payumPayment->setClientEmail($order->getCustomer()->getUsername());
                $payumPayment->setClientId($order->getCustomer()->getId());
                $payumPayment->setDescription($this->paymentDescriptionProvider->getPaymentDescription($payment));
                $payumPayment->setDetails($payment->getDetails());

                $this->gateway->execute($convert = new Convert($payumPayment, 'array', $request->getToken()));
                $payment->setDetails($convert->getResult());
            }
        }

        $details = ArrayObject::ensureArrayObject($payment->getDetails());

        try {
            $request->setModel($details);
            $this->gateway->execute($request);
        } finally {
            $payment->setDetails((array) $details);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request): bool
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof PaymentInterface
        ;
    }
}