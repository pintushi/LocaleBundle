<?php

declare(strict_types=1);

namespace Pintushi\Bundle\CustomerBundle\Entity;

interface CustomerAwareInterface
{
    /**
     * @return CustomerInterface|null
     */
    public function getCustomer(): ?CustomerInterface;

    /**
     * @param CustomerInterface|null $customer
     */
    public function setCustomer(?CustomerInterface $customer): void;
}
