<?php

namespace Pintushi\Bundle\MaintenanceBundle\Entity;

use Pintushi\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Pintushi\Bundle\UserBundle\Entity\TimestampableTrait;
use Pintushi\Bundle\UserBundle\Entity\ToggleableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

/**
 * @Config(
 *  defaultValues={
 *      "security"={
 *          "type"="ACL",
 *          "group_name"="",
 *          "category"="maintenance",
 *      },
 *      "ownership"={
 *              "owner_type"="ORGANIZATION",
 *              "owner_field_name"="organization",
 *              "owner_column_name"="organization_id",
 *       }
 *  }
 * )
 */
class ServiceGroup implements ServiceGroupInterface
{
    use TimestampableTrait, OrganizationAwareTrait, ToggleableTrait;

    protected $id;

    protected $name;

    protected $services;

    protected $position;

    public function __construct()
    {
        $this->services = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }


    /**
     * {@inheritdoc}
     */
    public function addService(ServiceInterface $serviceType)
    {
        if (!$this->hasService($serviceType)) {
            $this->services->add($serviceType);
            $serviceType->setGroup($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeService(ServiceInterface $serviceType)
    {
        if ($this->hasService($serviceType)) {
            $this->services->removeElement($serviceType);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasService(ServiceInterface $serviceType)
    {
        return $this->services->contains($serviceType);
    }

    public function getServices(): Collection
    {
        return $this->services;
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }
}
