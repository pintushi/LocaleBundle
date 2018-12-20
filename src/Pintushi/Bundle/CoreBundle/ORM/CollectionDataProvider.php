<?php

namespace Pintushi\Bundle\CoreBundle\ORM;

use Doctrine\Common\Persistence\ManagerRegistry;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\ContextAwareQueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryResultCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\RuntimeException;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;

use Pintushi\Bundle\SecurityBundle\ORM\Walker\AclHelper;

/**
 * a copy from api platform, but add acl support
 */
class CollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $managerRegistry;
    private $collectionExtensions;
    private $aclHelper;
    private $resourceMetadataFactory;

    /**
     * @param QueryCollectionExtensionInterface[]|ContextAwareQueryCollectionExtensionInterface[] $collectionExtensions
     */
    public function __construct(ManagerRegistry $managerRegistry, /* iterable */ $collectionExtensions = [], AclHelper $aclHelper, ResourceMetadataFactoryInterface $resourceMetadataFactory)
    {
        $this->managerRegistry = $managerRegistry;
        $this->collectionExtensions = $collectionExtensions;
        $this->aclHelper = $aclHelper;
        $this->resourceMetadataFactory = $resourceMetadataFactory;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return null !== $this->managerRegistry->getManagerForClass($resourceClass);
    }

     /**
     * {@inheritdoc}
     *
     * @throws RuntimeException
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        $manager = $this->managerRegistry->getManagerForClass($resourceClass);

        $repository = $manager->getRepository($resourceClass);
        if (!method_exists($repository, 'createQueryBuilder')) {
            throw new RuntimeException('The repository class must have a "createQueryBuilder" method.');
        }

        $queryBuilder = $repository->createQueryBuilder('o');
        $queryNameGenerator = new QueryNameGenerator();
        foreach ($this->collectionExtensions as $extension) {
            $extension->applyToCollection($queryBuilder, $queryNameGenerator, $resourceClass, $operationName, $context);

            if ($extension instanceof QueryResultCollectionExtensionInterface && $extension->supportsResult($resourceClass, $operationName, $context)) {
                return $extension->getResult($queryBuilder, $resourceClass, $operationName, $context);
            }
        }

        $resourceMetadata = $this->resourceMetadataFactory->create($resourceClass);
        $isAclEnabled =  $resourceMetadata->getCollectionOperationAttribute($operationName, 'acl_enabled', false);

        $query = $queryBuilder->getQuery();
        if ($isAclEnabled) {
            $query = $this->aclHelper->apply($query);
        }

        return $query->getResult();
    }
}
