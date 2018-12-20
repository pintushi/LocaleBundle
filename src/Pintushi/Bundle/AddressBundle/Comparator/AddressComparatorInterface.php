<?php

namespace Pintushi\Component\Address\Comparator;

use Pintushi\Bundle\AddressBundle\Entity\Address;

interface AddressComparatorInterface
{
    public function equal(Address $firstAddress, Address $secondAddress): bool;
}
