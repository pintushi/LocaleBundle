<?php

namespace Pintushi\Bundle\MaintenanceBundle\Entity;

use Pintushi\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Pintushi\Bundle\UserBundle\Entity\TimestampableTrait;
use Pintushi\Bundle\ProductBundle\Entity\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Pintushi\Bundle\ProductBundle\Entity\ProductInterface;

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
 */
class Service implements ServiceInterface
{
    use TimestampableTrait, OrganizationAwareTrait;

    protected $id;

    protected $name;

    protected $description;

    protected $enabled;

    protected $price;

    protected $products;

    protected $group;

    protected $blocks;

    protected $position;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->blocks = new ArrayCollection();
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
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    protected $priority;

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param mixed $priority
     */
    public function setPriority($priority): void
    {
        $this->priority = $priority;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
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
    public function addProduct(ProductInterface $product)
    {
        if (!$this->hasProduct($product)) {
            $this->products->add($product);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeProduct(ProductInterface $product)
    {
        if ($this->hasProduct($product)) {
            $this->products->removeElement($product);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasProduct(ProductInterface $product)
    {
        return $this->products->contains($product);
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function getGroup(): ?ServiceGroupInterface
    {
        return $this->group;
    }

    public function setGroup(?ServiceGroupInterface $group): void
    {
        $this->group = $group;
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

    /**
     * @return mixed
     */
    public function getBlocks()
    {
        return $this->block;
    }

    /**
     * @param mixed $block
     *
     * @return self
     */
    public function setBlocks($block)
    {
        $this->block = $block;

        return $this;
    }
}
