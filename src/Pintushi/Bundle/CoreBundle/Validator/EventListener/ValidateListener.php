<?php

declare(strict_types=1);

namespace Pintushi\Bundle\CoreBundle\Validator\EventListener;

use Videni\Bundle\RestBundle\Validator\Exception\ValidationException;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use Videni\Bundle\RestBundle\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Pintushi\Bundle\CoreBundle\Validator\ValidationHelper;
use Pintushi\Bundle\SecurityBundle\Owner\Metadata\OwnershipMetadataProvider;
use Pintushi\Bundle\OrganizationBundle\Validator\Constraints\Owner;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

class ValidateListener
{
    private $validator;
    private $symfonyValidator;
    private $resourceMetadataFactory;
    private $ownershipMetadataProvider;
    private $validationHelper;

    public function __construct(
        ValidatorInterface $validator,
        SymfonyValidatorInterface $symfonyValidator,
        ResourceMetadataFactoryInterface $resourceMetadataFactory,
        OwnershipMetadataProvider $ownershipMetadataProvider,
        ValidationHelper $validationHelper
    ) {
        $this->symfonyValidator = $symfonyValidator;
        $this->validator = $validator;
        $this->resourceMetadataFactory = $resourceMetadataFactory;
        $this->ownershipMetadataProvider = $ownershipMetadataProvider;
        $this->validationHelper = $validationHelper;
    }

    /**
     * Validates data returned by the controller if applicable.
     *
     * @throws ValidationException
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        if ($request->isMethodSafe(false)
            || $request->isMethod('DELETE')
            || !($attributes = RequestAttributesExtractor::extractAttributes($request))
            || !$attributes['receive']
        ) {
            return;
        }

        $data = $event->getControllerResult();
        $resourceMetadata = $this->resourceMetadataFactory->create($attributes['resource_class']);
        $validationGroups = $resourceMetadata->getOperationAttribute($attributes, 'validation_groups', null, true);

        $this->validator->validate($data, ['groups' => $validationGroups]);

        $this->validateOwner($data, $validationGroups);
    }

    protected function validateOwner($data, $validationGroups)
    {
        if (!is_object($data)) {
            return ;
        }

        $fieldName = $this->ownershipMetadataProvider->getMetadata(get_class($data))->getOwnerFieldName();
        if (!$fieldName) {
            return;
        }

        if (!$this->validationHelper->hasValidationConstraintForClass($data, Owner::class)) {
            $violations = $this->symfonyValidator->validate(
                $data,
                [
                    new Owner(['groups' => ['pintushi']])
                ],
                $validationGroups
            );

            if (0 !== \count($violations)) {
                throw new ValidationException($violations);
            }
        }
    }
}
