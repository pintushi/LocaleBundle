<?php

namespace Pintushi\Bundle\ProductBundle\Entity;

use Pintushi\Bundle\UserBundle\Entity\TimestampableTrait;
use Pintushi\Bundle\UserBundle\Entity\ToggleableTrait;
use Pintushi\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Webmozart\Assert\Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Pintushi\Bundle\AutoBundle\Entity\CarSeries;
use Pintushi\Bundle\FileBundle\Annotation as FileAnnoation;

/**
 * @Config(
 *  defaultValues={
 *      "security"={
 *          "type"="ACL",
 *          "group_name"="",
 *          "category"="taxon",
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
class Product implements ProductInterface
{
    use TimestampableTrait, ToggleableTrait, OrganizationAwareTrait;

    /**
     * @var int
     */
    protected $version = 1;

    protected $id;

    protected $name;

    protected $description;

    /**
     * @FileAnnoation\Link()
     */
    protected $image;

    protected $price;

    /**
     * @var int
     */
    protected $onHold = 0;

    /**
     * @var int
     */
    protected $onHand = 0;

    /**
     * @var bool
     */
    protected $tracked = false;

    protected $autoSeries;

    protected $service;

    public function __construct()
    {
        $this->autoSeries = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName(): string
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

    /**
     * @return mixed
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

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
     * {@inheritdoc}
     */
    public function getVersion(): ?int
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function setVersion(?int $version): void
    {
        $this->version = $version;
    }

    /**
     * {@inheritdoc}
     */
    public function isInStock(): bool
    {
        return 0 < $this->onHand;
    }

    /**
     * {@inheritdoc}
     */
    public function getOnHold(): ?int
    {
        return $this->onHold;
    }

    /**
     * {@inheritdoc}
     */
    public function setOnHold(?int $onHold): void
    {
        $this->onHold = $onHold;
    }

    /**
     * {@inheritdoc}
     */
    public function getOnHand(): ?int
    {
        return $this->onHand;
    }

    /**
     * {@inheritdoc}
     */
    public function setOnHand(?int $onHand): void
    {
        $this->onHand = (0 > $onHand) ? 0 : $onHand;
    }

    /**
     * {@inheritdoc}
     */
    public function isTracked(): bool
    {
        return $this->tracked;
    }

    /**
     * {@inheritdoc}
     */
    public function setTracked(bool $tracked): void
    {
        Assert::boolean($tracked);

        $this->tracked = $tracked;
    }

    /**
     * {@inheritdoc}
     */
    public function getInventoryName(): ?string
    {
        return $this->getName();
    }

    /**
     * @return mixed
     */
    public function getAutoSeries()
    {
        return $this->autoSeries;
    }

    public function addAutoSeries(CarSeries $series)
    {
        if (!$this->autoSeries->contains($series)) {
            $this->autoSeries->add($series);
        }

        return $this;
    }

    public function removeAutoSeries(CarSeries $series)
    {
        if ($this->autoSeries->contains($series)) {
            $this->autoSeries->removeElement($series);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     *
     * @return self
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     *
     * @return self
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }
}
