<?php

namespace Pintushi\Bundle\CoreBundle\Validator;

use Symfony\Component\Validator\Mapping\Loader\LoaderInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;
use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Symfony\Component\Validator\Mapping\Cache\CacheInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * add owner constraint to all classes that have owner property
 */
class OwnerMetadataFactory extends LazyLoadingMetadataFactory
{
    private $ownershipMetadataProvider;
    private $validatorHelper;

    public function __construct(
        LoaderInterface $loader = null,
        CacheInterface $cache = null,
        OwnershipMetadataProvider $ownershipMetadataProvider = null,
        ValidationHelper $validatorHelper = null
    ) {
        parent::__construct($loader, $cache);

        $this->ownershipMetadataProvider = $ownershipMetadataProvider;
        $this->validatorHelper = $validatorHelper;
    }

    public function getMetadataFor($value)
    {
        $metadata = parent::getMetadataFor($value);

        $resourceClass = is_object($value)? get_class($value): $value;
        if (!$this->ownerPropertyHelper) {
            return $metadata;
        }

        $fieldName = $this->ownershipMetadataProvider->getMetadata($entityClass)->getOwnerFieldName();
        if (!$fieldName) {
            return $metadata;
        }
         // add owner validator
        if (!$this->validationHelper->hasValidationConstraintForClass($value, Owner::class)) {
            $metadata->addConstraint(new Owner(['groups' => ['pintushi']]));
        }

        return $metadata;
    }
}
