<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PayumBundle\Provider;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\PaymentBundle\Entity\PaymentInterface;
use Symfony\Component\Translation\TranslatorInterface;

final class PaymentDescriptionProvider implements PaymentDescriptionProviderInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentDescription(PaymentInterface $payment): string
    {
        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        return $this->translator->transChoice(
            'pintushi.payum_action.payment.description',
            $order->getItems()->count(),
            [
                '%items%' => $order->getItems()->count(),
                '%total%' => round($order->getTotal() / 100, 2),
            ]
        );
    }
}
