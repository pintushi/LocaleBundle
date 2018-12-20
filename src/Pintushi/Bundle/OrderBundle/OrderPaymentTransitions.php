<?php

namespace Pintushi\Bundle\OrderBundle;

final class OrderPaymentTransitions
{
    const GRAPH = 'pintushi_order_payment';

    const TRANSITION_REQUEST_PAYMENT = 'request_payment';
    const TRANSITION_CANCEL = 'cancel';
    const TRANSITION_PAY = 'pay';
    const TRANSITION_REFUND = 'refund';

    private function __construct()
    {
    }
}
