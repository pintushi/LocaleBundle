<?php

namespace Pintushi\Bundle\MaintenanceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;

/**
 * vidy videni <videni@foxmail>
 */
interface ServiceGroupInterface extends OrganizationAwareInterface
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param mixed $name
     */
    public function setName($name): void;

    /**
     * {@inheritdoc}
     */
    public function addService(ServiceInterface $serviceType);

    /**
     * {@inheritdoc}
     */
    public function removeService(ServiceInterface $serviceType);

    /**
     * {@inheritdoc}
     */
    public function hasService(ServiceInterface $serviceType);


    public function getServices(): Collection;


     /**
     * {@inheritdoc}
     */
    public function getPosition(): ?int;


    /**
     * {@inheritdoc}
     */
    public function setPosition(?int $position): void;
}
