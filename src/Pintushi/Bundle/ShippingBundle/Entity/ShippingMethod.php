<?php

namespace Pintushi\Bundle\ShippingBundle\Entity;

use Pintushi\Bundle\UserBundle\Entity\TimestampableTrait;
use Pintushi\Bundle\UserBundle\Entity\ToggleableTrait;
use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Pintushi\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;

/**
 * @Config(
 *  defaultValues={
 *      "security"={
 *          "type"="ACL",
 *          "group_name"="",
 *          "category"="shipping",
 *      },
 *      "ownership"={
 *              "owner_type"="ORGANIZATION",
 *              "owner_field_name"="organization",
 *              "owner_column_name"="organization_id",
 *      },
 *  }
 * )
 */
class ShippingMethod implements ShippingMethodInterface, OrganizationAwareInterface
{
    use TimestampableTrait, ToggleableTrait, OrganizationAwareTrait;

    /**
     * @var mixed
     */
    protected $id;

    protected $amount = 0;

    protected $description;

    protected $name;

    protected $type;

    /**
     * @var int
     */
    protected $position;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
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
    public function setPosition(?int $position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount(?int $amount)
    {
        $this->amount = $amount;

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

     /**
     * {@inheritdoc}
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType(string $type): void
    {
        if (!in_array($type, array(self::TYPE_SHOP, self::TYPE_HOME))) {
            throw new \InvalidArgumentException("Invalid shipping method type");
        }

        $this->type = $type;
    }
}
