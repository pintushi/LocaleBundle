<?php

namespace Pintushi\Bundle\AddressBundle\Form\EventSubscriber;

use Pintushi\Bundle\LocationBundle\Repository\LocationRepository;
use Pintushi\Component\Address\Model\AddressInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Webmozart\Assert\Assert;

class AddressTypeSubscriber implements EventSubscriberInterface
{
    /**
     * @var LocationRepository
     */
    protected $locationRepository;

    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SUBMIT => 'setNames',
        ];
    }

    public function setNames(FormEvent $event): void
    {
        /**
         * @var AddressInterface $address
         */
        $address= $event->getData();
        $regionCode = $address->getRegionCode();
        if (!$regionCode) {
            return;
        }
        $region = $this->locationRepository->findOneByCode($address->getRegionCode());

        if (!$region) {
            return;
        }

        $places = $this->locationRepository->findFullPlaces($region);

        Assert::count($places, 4, '通过区查询地址全称，必须返回国家、省、市、区四级数据');
        list($country, $province, $city, $region)  = $places;

        $address->setCountryCode($country->getCode());
        $address->setProvince($province->getName());
        $address->setProvinceCode($province->getCode());
        $address->setCity($city->getName());
        $address->setCityCode($city->getCode());
        $address->setRegion($region->getName());
        $address->setRegionCode($region->getCode());
    }
}
