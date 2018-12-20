<?php

namespace Pintushi\Bundle\CoreBundle\Metadata;

use Pintushi\Bundle\SecurityBundle\ORM\DoctrineHelper;
use Pintushi\Bundle\SecurityBundle\Authentication\TokenAccessorInterface;
use Pintushi\Bundle\SecurityBundle\Owner\Metadata\OwnershipMetadata;
use Pintushi\Bundle\SecurityBundle\Owner\Metadata\OwnershipMetadataInterface;
use Pintushi\Bundle\SecurityBundle\Owner\Metadata\OwnershipMetadataProviderInterface;

/**
 *
 * 根据当前登录用户，获取实体的owner属性。 比如User的ownership type为BusinessUnit,
 * 那么它的owner属性为organizaiton, owner。这个类可以帮助动态创建序列化、验证规则。
 *
 */
class OwnershipPropertyHelper
{
     /** @var DoctrineHelper */
    protected $doctrineHelper;

    /** @var OwnershipMetadataProviderInterface */
    protected $ownershipMetadataProvider;

    /** @var TokenAccessorInterface */
    protected $tokenAccessor;

    public function __construct(
        DoctrineHelper $doctrineHelper,
        OwnershipMetadataProviderInterface $ownershipMetadataProvider,
        TokenAccessorInterface $tokenAccessor
    ) {
        $this->doctrineHelper = $doctrineHelper;
        $this->ownershipMetadataProvider = $ownershipMetadataProvider;
        $this->tokenAccessor = $tokenAccessor;
    }

    public function shouldCheckOwnership($resourceClass)
    {
        if (!$this->tokenAccessor->getUser()) {
            return false;
        }
        if (!$metadata = $this->getMetadata($resourceClass)) {
            return false;
        }

        return $metadata->hasOwner();
    }

    public function getOwnershipProperties($resourceClass)
    {
        $properties = [];
        if (!$this->shouldCheckOwnership($resourceClass)) {
            return [];
        }

        $metadata = $this->getMetadata($resourceClass);
        $fieldName = $metadata->getOwnerFieldName();

        $properties[]= $fieldName;

        if (!$metadata->isOrganizationOwned()) {
            $properties [] = $metadata->getOrganizationFieldName();
        }

        return $properties;
    }

    /**
     * Get metadata for entity
     *
     * @param object|string $entity
     *
     * @return bool|OwnershipMetadataInterface
     * @throws \LogicException
     */
    public function getMetadata($entity)
    {
        if (is_object($entity)) {
            $entity = ClassUtils::getClass($entity);
        }

        if (!$this->doctrineHelper->isManageableEntity($entity)) {
            return false;
        }

        $metadata = $this->ownershipMetadataProvider->getMetadata($entity);

        return $metadata->hasOwner()
            ? $metadata
            : false;
    }
}
