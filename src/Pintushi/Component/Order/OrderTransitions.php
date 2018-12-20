<?php

namespace Pintushi\Component\Order;

final class OrderTransitions
{
    const GRAPH = 'pintushi_order';

    const TRANSITION_CREATE = 'create';
    const TRANSITION_CANCEL = 'cancel';
    const TRANSITION_FULFILL = 'fulfill';

    private function __construct()
    {
    }
}
