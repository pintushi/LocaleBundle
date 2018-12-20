<?php

namespace Pintushi\Bundle\ProductBundle\Provider;

use Pintushi\Bundle\CarBundle\Entity\CarModel;
use Pintushi\Bundle\ProductBundle\Entity\ProductInterface;
use Pintushi\Bundle\MaintenanceBundle\Entity\ServiceInterface;
use Pintushi\Bundle\ProductBundle\Repository\ProductRepositoryInterface;
use Pintushi\Bundle\InventoryBundle\Checker\AvailabilityCheckerInterface;

class ProductProvider implements ProductProviderInterface
{
    /**
     * @var EntityRepository
     */
    protected $productRepository;

    /**
     * @var AvailabilityCheckerInterface
     */
    protected $availabilityChecker;

    public function __construct(ProductRepositoryInterface $productRepository, AvailabilityCheckerInterface $availabilityChecker)
    {
        $this->productRepository = $productRepository;
        $this->availabilityChecker=$availabilityChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableProducts(ServiceInterface $serviceType, CarModel $carModel)
    {
        $products = $this->productRepository->findBySeviceAndAutoSeries($serviceType, $carModel);

        $available = $products->filter(function ($product) {
            return $this->availabilityChecker->isStockSufficient($product, 1);
        });

        return $available;
    }
}
