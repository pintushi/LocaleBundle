<?php

namespace Pintushi\Bundle\ShippingBundle;

final class ShipmentTransitions
{
    const GRAPH = 'pintushi_shipment';

    const TRANSITION_CREATE = 'create';
    const TRANSITION_SHIP = 'ship';
    const TRANSITION_CANCEL = 'cancel';

    private function __construct()
    {
    }
}
