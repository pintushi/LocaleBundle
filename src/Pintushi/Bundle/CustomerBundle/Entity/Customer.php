<?php

declare(strict_types=1);

namespace Pintushi\Bundle\CustomerBundle\Entity;

use Pintushi\Bundle\UserBundle\Entity\TimestampableTrait;
use Pintushi\Bundle\UserBundle\Entity\ToggleableTrait;
use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Pintushi\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Pintushi\Bundle\AddressBundle\Entity\Address;
use Doctrine\Common\Collections\Collection;
use Pintushi\Bundle\UserBundle\Entity\AbstractUser;
use Doctrine\Common\Collections\Criteria;
use Pintushi\Bundle\AutoBundle\Entity\CarModel;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;

/**
 * @Config(
 *  defaultValues={
 *      "security"={
 *          "type"="ACL",
 *          "group_name"="",
 *          "category"="customer",
 *      },
 *    "ownership"={
 *              "owner_type"="ORGANIZATION",
 *              "owner_field_name"="organization",
 *              "owner_column_name"="organization_id",
 *     },
 *  }
 * )
 */
class Customer extends AbstractUser implements CustomerInterface, OrganizationAwareInterface
{
    use OrganizationAwareTrait;

    const ROLE_CUSTOMER = 'ROLE_CUSTOMER';

    /**
     * @var CustomerGroupInterface|null
     */
    protected $group;

    /**
     * @var \DateTimeInterface|null
     */
    protected $birthday;

    /**
     * @var Address
     */
    protected $defaultAddress;

    protected $defaultAuto;

    /**
     * @var Collection|Address[]
     */
    protected $addresses;

    protected $orders;

    protected $autos;

    public function __construct()
    {
        parent::__construct();

        $this->createdAt = new \DateTime();
        $this->orders = new ArrayCollection();
        $this->autos = new ArrayCollection();
        $this->addresses = new ArrayCollection();
    }


    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getEmail();
    }

    /**
     * {@inheritdoc}
     */
    public function getFullName(): string
    {
        return trim(sprintf('%s%s', $this->lastName, $this->firstName));
    }

    /**
     * {@inheritdoc}
     */
    public function getGroup(): ?CustomerGroupInterface
    {
        return $this->group;
    }

    /**
     * {@inheritdoc}
     */
    public function setGroup(?CustomerGroupInterface $group): void
    {
        $this->group = $group;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param \DateTimeInterface|null $birthday
     *
     * @return self
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getRoles()
    {
        return [self::ROLE_CUSTOMER];
    }

     /**
     * {@inheritdoc}
     */
    public function getDefaultAddress(): ?Address
    {
        return $this->defaultAddress;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultAddress(?Address $defaultAddress = null): void
    {
        $this->defaultAddress = $defaultAddress;

        if (null !== $defaultAddress) {
            $this->addAddress($defaultAddress);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addAddress(Address $address): void
    {
        if (!$this->hasAddress($address)) {
            $this->addresses[] = $address;
            $address->setCustomer($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeAddress(Address $address): void
    {
        $this->addresses->removeElement($address);
        $address->setCustomer(null);
    }

    /**
     * {@inheritdoc}
     */
    public function hasAddress(Address $address): bool
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("id", $address->getId()));

        $match = $this->addresses->matching($criteria);

        return count($match) > 0 ? true: false;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * {@inheritdoc}
     */
    public function hasOrder(OrderInterface $order)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("id", $order->getId()));

        $match = $this->orders->matching($criteria);

        return count($match) > 0 ? true: false;
    }

    public function getAutos(): Collection
    {
        return $this->autos;
    }

    /**
     * {@inheritdoc}
     */
    public function addAuto(CarModel $carModel)
    {
        if (!$this->hasAuto($carModel)) {
            $this->autos->add($carModel);
            $carModel->addCustomer($this);

            $this->setDefaultAuto($carModel);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeAuto(CarModel $carModel)
    {
        if ($this->hasAuto($carModel)) {
            $this->autos->removeElement($carModel);
            $carModel->removeCustomer($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasAuto(CarModel $carModel)
    {
        return $this->autos->contains($carModel);
    }

    /**
     * @return mixed
     */
    public function getDefaultAuto()
    {
        return $this->defaultAuto;
    }

    /**
     * @param mixed $defaultAuto
     *
     * @return self
     */
    public function setDefaultAuto($defaultAuto)
    {
        $this->defaultAuto = $defaultAuto;

        return $this;
    }
}
