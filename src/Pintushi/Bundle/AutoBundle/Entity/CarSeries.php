<?php

namespace  Pintushi\Bundle\AutoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Pintushi\Bundle\UserBundle\Entity\TimestampableTrait;
use Pintushi\Bundle\UserBundle\Entity\ToggleableTrait;
use Pintushi\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Pintushi\Bundle\FileBundle\Annotation as FileAnnoation;

/**
 * @FileAnnoation\File()
 */
class CarSeries
{
    use TimestampableTrait, ToggleableTrait, OrganizationAwareTrait;

    private $id;

    private $name;

    private $carModels;

    /**
     * @FileAnnoation\Link()
     */
    private $image;

    private $brand;

    private $products;

    public function __construct()
    {
        $this->carModels = new ArrayCollection();
        $this->products = new ArrayCollection();
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
    public function getCarModels()
    {
        return $this->carModels;
    }

    public function addCarModel(CarModel $carModel)
    {
        if (!$this->carModels->contains($carModel)) {
            $this->carModels->add($carModel);
            $carModel->setCarSeries($this);
        }

        return $this;
    }

    public function removeCarModel(CarModel $carModel)
    {
        if ($this->carModels->contains($carModel)) {
            $this->carModels->removeElement($carModel);
        }

        return $this;
    }

    public function getBrand(): CarBrand
    {
        return $this->brand;
    }

    /**
     * @param mixed $carBrand
     */
    public function setBrand(CarBrand $carBrand): void
    {
        $carBrand->addCarSeries($this);

        $this->brand = $carBrand;
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $this->setOrganization($this->getBrand()->getOrganization());
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return mixed
     */
    public function getBrandName()
    {
        return $this->getBrand()->getName();
    }
}
