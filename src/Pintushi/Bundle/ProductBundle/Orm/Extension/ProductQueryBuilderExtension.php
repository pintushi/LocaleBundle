<?php

namespace Pintushi\Bundle\ProductBundle\Orm\Extension;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use Pintushi\Bundle\ProductBundle\Repository\ProductRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Pintushi\Bundle\ProductBundle\Entity\Product;

class ProductQueryBuilderExtension implements QueryCollectionExtensionInterface
{
    private $productRepository;
    private $request;

    public function __construct(
        ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass = null, string $operationName = null, array $context = [])
    {
        if (Product::class !== $resourceClass) {
            return;
        }

        if ('get_inventory' === $operationName) {
            $this->productRepository->addTrackedQuery($queryBuilder);
        }
    }
}
