<?php

namespace Pintushi\Bundle\ProductBundle\Provider;

use Pintushi\Bundle\CarBundle\Entity\CarModel;
use Pintushi\Bundle\ProductBundle\Entity\ProductInterface;
use Pintushi\Bundle\MaintenanceBundle\Entity\ServiceInterface;

interface ProductProviderInterface
{
    public function getAvailableProducts(ServiceInterface $serviceType, CarModel $carModel);
}
