<?php

namespace Pintushi\Bundle\OrderBundle;

final class OrderShippingTransitions
{
    const GRAPH = 'pintushi_order_shipping';

    const TRANSITION_REQUEST_SHIPPING = 'request_shipping';
    const TRANSITION_PARTIALLY_SHIP = 'partially_ship';
    const TRANSITION_SHIP = 'ship';
    const TRANSITION_CANCEL = 'cancel';

    private function __construct()
    {
    }
}
