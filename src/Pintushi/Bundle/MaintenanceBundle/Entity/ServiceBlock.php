<?php

namespace Pintushi\Bundle\MaintenanceBundle\Entity;

use Pintushi\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Pintushi\Bundle\UserBundle\Entity\ToggleableTrait;
use Videni\Bundle\FileBundle\Annotation as FileAnnoation;

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
 *      }
 *  }
 * )
 * @FileAnnoation\File()
 */
class ServiceBlock
{
    use OrganizationAwareTrait, ToggleableTrait;

    private $id;

    private $services;

    protected $label;

    /**
     * @FileAnnoation\Link()
     */
    protected $image;

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
     * {@inheritdoc}
     */
    public function addService(ServiceInterface $serviceType)
    {
        if (!$this->hasService($serviceType)) {
            $this->services->add($serviceType);
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
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     *
     * @return self
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     *
     * @return self
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     *
     * @return self
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }
}
