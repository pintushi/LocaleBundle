<?php

namespace Pintushi\Bundle\OrderBundle\OrderProcessing;

use Pintushi\Bundle\ShippingBundle\Entity\ShipmentInterface;
use Pintushi\Bundle\ShippingBundle\Entity\Shipment;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Component\Order\Processor\OrderProcessorInterface;
use Pintushi\Bundle\ShippingBundle\Exception\UnresolvedDefaultShippingMethodException;
use Pintushi\Bundle\ShippingBundle\Resolver\DefaultShippingMethodResolverInterface;

final class OrderShipmentProcessor implements OrderProcessorInterface
{
    /**
     * @var DefaultShippingMethodResolverInterface
     */
    private $defaultShippingMethodResolver;

    public function __construct(DefaultShippingMethodResolverInterface $defaultShippingMethodResolver)
    {
        $this->defaultShippingMethodResolver = $defaultShippingMethodResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function process(OrderInterface $order): void
    {
        if ($order->getShipment() == null) {
            $shipment = new Shipment();
            $shipment->setOrder($order);
            $shipment->setMethod($this->defaultShippingMethodResolver->getDefaultShippingMethod());

            $order->setShipment($shipment);
        }
    }
}
