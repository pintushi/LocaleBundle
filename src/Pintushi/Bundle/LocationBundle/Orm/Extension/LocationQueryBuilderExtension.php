<?php

namespace Pintushi\Bundle\LocationBundle\Orm\Extension;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;
use Pintushi\Bundle\LocationBundle\Entity\Location;
use Pintushi\Bundle\LocationBundle\Repository\LocationRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LocationQueryBuilderExtension implements QueryCollectionExtensionInterface
{
    private $locationRepository;
    private $request;

    public function __construct(
        LocationRepository $locationRepository
    ) {
        $this->locationRepository = $locationRepository;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass = null, string $operationName = null, array $context = [])
    {
        if (Location::class !== $resourceClass) {
            return;
        }

        if ('get_province' === $operationName) {
            $queryBuilder->andWhere('o.level = :level')
                ->setParameter('level', Location::LEVEL_PROVICE);
        }

        if ('get_city' === $operationName) {
            $location = $this->findOr404(Location::LEVEL_PROVICE);

            $this->locationRepository->addChildrenQuery($queryBuilder, $location, Location::LEVEL_CITY);
        }

        if ('get_area' == $operationName) {
            $location = $this->findOr404(Location::LEVEL_CITY);

            $this->locationRepository->addChildrenQuery($queryBuilder, $location, Location::LEVEL_REGION);
        }
    }

    public function findOr404($level)
    {
        $location = $this->locationRepository->findOneBy(
            [
                    'code' => $this->request->attributes->get('code'),
                    'level' => $level
            ]
        );

        if (!$location) {
            throw new NotFoundHttpException();
        }

        return $location;
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }
}
