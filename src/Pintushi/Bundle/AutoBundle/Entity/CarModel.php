<?php

namespace Pintushi\Bundle\AutoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Pintushi\Component\Core\Model\ImageSubjectInterface;
use Pintushi\Component\Core\Model\Image;
use Pintushi\Component\Core\Model\BaseImageInterface;
use Pintushi\Component\Core\Model\ImageTrait;
use Pintushi\Bundle\UserBundle\Entity\TimestampableTrait;
use Pintushi\Bundle\CustomerBundle\Entity\CustomerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Videni\Bundle\FileBundle\Annotation as FileAnnoation;

/**
 * @FileAnnoation\File()
 */
class CarModel
{
    use TimestampableTrait;

    private $name;

    /**
     * @var integer
     */
    private $id;

    private $carSeries;

    /**
     * @var string
     */
    private $fullName;

    private $customers;

    /**
     * @FileAnnoation\Link()
     */
    private $image;

     /**
     * @FileAnnoation\Link()
     */
    private $brandLogo;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

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
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getCarSeries(): CarSeries
    {
        return $this->carSeries;
    }

    public function setCarSeries(CarSeries $carSeries): void
    {
        $carSeries->addCarModel($this);

        $this->carSeries = $carSeries;
    }

    public function getFullName(): ?string
    {
        if (!$this->fullName) {
            $this->setFullName();
        }

        return $this->fullName;
    }

    public function setFullName()
    {
        $series = $this->getCarSeries();
        $this->fullName = sprintf('%s %s %s', $series->getBrand()->getName(), $series->getName(), $this->getName());

        return $this;
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $this->setFullName();

        $series = $this->getCarSeries();
        $this->setBrandLogo($series->getBrand()->getImage());
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

    public function getCustomers()
    {
        return $this->customers;
    }

     /**
     * @param CustomerInterface $customer
     *
     * @return $this
     */
    public function addCustomer(CustomerInterface $customer)
    {
        if (!$this->customers->contains($customer)) {
            $this->customers->add($customer);
        }

        return $this;
    }

    /**
     * @param Customer $customer
     *
     * @return $this
     */
    public function removeCustomer(CustomerInterface $customer)
    {
        if ($this->customers->contains($customer)) {
            $this->customers->removeElement($customer);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBrandLogo()
    {
        return $this->brandLogo;
    }

    /**
     * @param mixed $brandLogo
     *
     * @return self
     */
    public function setBrandLogo($brandLogo)
    {
        $this->brandLogo = $brandLogo;

        return $this;
    }
}
