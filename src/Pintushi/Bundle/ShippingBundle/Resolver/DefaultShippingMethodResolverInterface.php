<?php

namespace Pintushi\Bundle\ShippingBundle\Resolver;

use Pintushi\Bundle\ShippingBundle\Entity\ShippingMethodInterface;
use Pintushi\Bundle\ShippingBundle\Entity\ShipmentInterface;

interface DefaultShippingMethodResolverInterface
{
    public function getDefaultShippingMethod(ShipmentInterface $shipment = null): ?ShippingMethodInterface;
}
