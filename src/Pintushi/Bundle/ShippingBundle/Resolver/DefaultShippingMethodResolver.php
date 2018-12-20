<?php


namespace Pintushi\Bundle\ShippingBundle\Resolver;

use Pintushi\Bundle\ShippingBundle\Exception\UnresolvedDefaultShippingMethodException;
use Pintushi\Bundle\ShippingBundle\Entity\ShipmentInterface;
use Pintushi\Bundle\ShippingBundle\Repository\ShippingMethodRepositoryInterface;
use Pintushi\Bundle\ShippingBundle\Entity\ShippingMethodInterface;

final class DefaultShippingMethodResolver implements DefaultShippingMethodResolverInterface
{
    /**
     * @var ShippingMethodRepositoryInterface
     */
    private $shippingMethodRepository;

    public function __construct(ShippingMethodRepositoryInterface $shippingMethodRepository)
    {
        $this->shippingMethodRepository = $shippingMethodRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultShippingMethod(ShipmentInterface $shipment = null): ?ShippingMethodInterface
    {
        $shippingMethods = $this->shippingMethodRepository->findBy(['enabled' => true]);
        if (empty($shippingMethods)) {
            throw new UnresolvedDefaultShippingMethodException();
        }

        return $shippingMethods[0];
    }
}
