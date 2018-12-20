<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PayumBundle\Action\WechatPay;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Pintushi\Bundle\PaymentBundle\Entity\PaymentInterface;
use Payum\Core\Request\Convert;
use Pintushi\Bundle\PayumBundle\Provider\PaymentDescriptionProviderInterface;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Action\GatewayAwareAction;

final class ConvertPaymentAction extends GatewayAwareAction
{
    const OAUTH_PROVIDER = 'wechat';

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
     * @param Convert $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $payment = $request->getSource();

        $this->gateway->execute($httpRequest = new GetHttpRequest);

        $order = $payment->getOrder();

        $openId = $order->getCustomer()->getOAuthAccount(self::OAUTH_PROVIDER)->getIdentifier();

        $request->setResult([
            'body'              => $this->paymentDescriptionProvider->getPaymentDescription($payment),
            'out_trade_no'      => $order->getNumber(),
            'total_fee'         => $order->getTotal(),
            'spbill_create_ip'  => $httpRequest->clientIp,
            'open_id'           => $openId,
            'fee_type'          => $payment->getCurrencyCode()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request): bool
    {
        if (!$request instanceof Convert) {
            return false;
        }

        $source = $request->getSource();

        return $source instanceof PaymentInterface &&
            $source->getMethod()->getGatewayConfig()->getFactoryName()== 'wechat_pay'
        ;
    }
}
