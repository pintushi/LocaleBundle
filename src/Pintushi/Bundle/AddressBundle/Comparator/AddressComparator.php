<?php

namespace Pintushi\Component\Address\Comparator;

use Pintushi\Bundle\AddressBundle\Entity\Address;

final class AddressComparator implements AddressComparatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function equal(Address $firstAddress, Address $secondAddress): bool
    {
        return $this->normalizeAddress($firstAddress) === $this->normalizeAddress($secondAddress);
    }

    /**
     * @param Address $address
     *
     * @return array
     */
    private function normalizeAddress(Address $address): array
    {
        return array_map(function ($value) {
            return trim(strtolower($value));
        }, [
            $address->getUsername(),
            $address->getPhoneNumber(),
            $address->getProvinceCode(),
            $address->getCityCode(),
            $address->getRegionCode(),
            $address->getStreet(),
        ]);
    }
}
