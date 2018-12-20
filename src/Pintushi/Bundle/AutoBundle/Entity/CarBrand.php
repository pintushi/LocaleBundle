<?php

namespace  Pintushi\Bundle\AutoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Pintushi\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Pintushi\Bundle\UserBundle\Entity\TimestampableTrait;
use Pintushi\Bundle\UserBundle\Entity\ToggleableTrait;
use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Pintushi\Bundle\FileBundle\Annotation as FileAnnoation;

/**
 * @Config(
 *  defaultValues={
 *      "security"={
 *          "type"="ACL",
 *          "group_name"="",
 *          "category"="car",
 *      },
 *    "ownership"={
 *              "owner_type"="ORGANIZATION",
 *              "owner_field_name"="organization",
 *              "owner_column_name"="organization_id",
 *          },
 *  }
 * )
 * @FileAnnoation\File()
 */
class CarBrand implements OrganizationAwareInterface
{
    use OrganizationAwareTrait, TimestampableTrait, ToggleableTrait ;

    private $id;

    /**
     * @var string
     */
    protected $firstLetter;

    private $name;

    private $carSeries;

    /**
     * @FileAnnoation\Link()
     */
    private $image;

    public function __construct()
    {
        $this->carSeries = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    public function getFirstLetter(): string
    {
        return $this->firstLetter;
    }

    public function setFirstLetter(string $firstLetter)
    {
        $this->firstLetter = $firstLetter;

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
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function addCarSeries(CarSeries $carSeries)
    {
        if (!$this->carSeries->contains($carSeries)) {
            $this->carSeries->add($carSeries);
            $carSeries->setBrand($this);
        }

        return $this;
    }

    public function getCarSeries(): ArrayCollection
    {
        return $this->carSeries;
    }

    public function removeCarSeries(CarSeries $carSeries)
    {
        if ($this->carSeries->contains($carSeries)) {
            $this->carSeries->removeElement($carSeries);
        }

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
}
