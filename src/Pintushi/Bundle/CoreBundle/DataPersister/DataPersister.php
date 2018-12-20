<?php

declare(strict_types=1);

namespace Pintushi\Bundle\CoreBundle\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\Util\ClassInfoTrait;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager as DoctrineObjectManager;
use Pintushi\Bundle\OrganizationBundle\Ownership\OwnerDeletionManager;
use Pintushi\Bundle\SecurityBundle\Exception\ForbiddenException;

/**
 *  Stop deleting ownership entity
 */
class DataPersister implements DataPersisterInterface
{
    use ClassInfoTrait;

    private $managerRegistry;

    private $ownerDeletionManager;

    public function __construct(ManagerRegistry $managerRegistry, OwnerDeletionManager $ownerDeletionManager)
    {
        $this->managerRegistry = $managerRegistry;
        $this->ownerDeletionManager = $ownerDeletionManager;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data): bool
    {
        return null !== $this->getManager($data);
    }

    /**
     * {@inheritdoc}
     */
    public function persist($data)
    {
        if (!$manager = $this->getManager($data)) {
            return $data;
        }

        if (!$manager->contains($data)) {
            $manager->persist($data);
        }

        $manager->flush();
        $manager->refresh($data);

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data)
    {
        if (!$manager = $this->getManager($data)) {
            return;
        }
        $this->checkPermissions($data);

        $manager->remove($data);
        $manager->flush();
    }

    /**
     * Gets the Doctrine object manager associated with given data.
     *
     * @param mixed $data
     *
     * @return DoctrineObjectManager|null
     */
    private function getManager($data)
    {
        return \is_object($data) ? $this->managerRegistry->getManagerForClass($this->getObjectClass($data)) : null;
    }

      /**
     * Checks if a delete operation is allowed
     *
     * @param object        $entity
     * @throws ForbiddenException if a delete operation is forbidden
     */
    protected function checkPermissions($entity)
    {
        if ($this->ownerDeletionManager->isOwner($entity) && $this->ownerDeletionManager->hasAssignments($entity)) {
            throw new ForbiddenException('has assignments');
        }
    }
}
