<?php

namespace Pintushi\Bundle\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Pintushi\Bundle\ShippingBundle\Entity\ShippingMethodInterface;
use Videni\Bundle\RestBundle\Model\ResourceInterface;
use Pintushi\Bundle\OrganizationBundle\Entity\Organization as BaseOrganization;

/*
 *@Config(
 *   defaultValues={
 *      "security"={
 *          "type"="ACL",
 *          "group_name"="",
 *          "category"="account_management"
 *   }
 * )
 */
class Organization extends BaseOrganization
{
    protected $address;

    protected $shippingMethods;

    public function __construct()
    {
        parent::__construct();

        $this->shippingMethods = new ArrayCollection();
    }


     /**
     * {@inheritdoc}
     */
    public function hasShippingMethod(ShippingMethodInterface $shippingMethod)
    {
        return $this->shippingMethods->contains($shippingMethod);
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingMethods()
    {
        return $this->shippingMethods;
    }

    /**
     * {@inheritdoc}
     */
    public function addShippingMethod(ShippingMethodInterface $shippingMethod)
    {
        if (!$this->hasShippingMethod($shippingMethod)) {
            $shippingMethod->setOrganization($this);
            $this->shippingMethods->add($shippingMethod);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeShippingMethod(ShippingMethodInterface $shippingMethod)
    {
        if ($this->hasShippingMethod($shippingMethod)) {
            $shippingMethod->setOrganization(null);
            $this->shippingMethods->remove($shippingMethod);
        }
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     *
     * @return self
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }
}
