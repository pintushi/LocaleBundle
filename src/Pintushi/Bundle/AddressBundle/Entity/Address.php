<?php

namespace Pintushi\Bundle\AddressBundle\Entity;

use Pintushi\Bundle\UserBundle\Entity\TimestampableTrait;
use Pintushi\Bundle\CustomerBundle\Entity\CustomerInterface as BaseCustomerInterface;

class Address implements AddressInterface
{
     use TimestampableTrait;

    /**
     * @var mixed
     */
    protected $id;

    protected $username;

    /**
     * @var string
     */
    protected $countryCode;

     /**
     * @var string
     */
    protected $provinceCode;

    /**
     * @var string
     */
    protected $province;

    protected $cityCode;

    /**
     * @var string
     */
    protected $city;

    protected $regionCode;

    protected $region;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var CustomerInterface
     */
    protected $customer;

    /**
     * @var string
     */
    protected $phoneNumber;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function __clone()
    {
        $this->customer = null;
        $this->id = null;
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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     *
     * @return self
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getProvinceCode()
    {
        return $this->provinceCode;
    }

    /**
     * @param string $provinceCode
     *
     * @return self
     */
    public function setProvinceCode($provinceCode)
    {
        $this->provinceCode = $provinceCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param string $province
     *
     * @return self
     */
    public function setProvince($province)
    {
        $this->province = $province;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCityCode()
    {
        return $this->cityCode;
    }

    /**
     * @param mixed $cityCode
     *
     * @return self
     */
    public function setCityCode($cityCode)
    {
        $this->cityCode = $cityCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return self
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegionCode()
    {
        return $this->regionCode;
    }

    /**
     * @param mixed $regionCode
     *
     * @return self
     */
    public function setRegionCode($regionCode)
    {
        $this->regionCode = $regionCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     *
     * @return self
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     *
     * @return self
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     *
     * @return self
     */
    public function setPhoneNumber(?string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

     /**
     * {@inheritdoc}
     */
    public function getCustomer(): ?BaseCustomerInterface
    {
        return $this->customer;
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomer(?BaseCustomerInterface $customer)
    {
        $this->customer = $customer;

        return $this;
    }
}
