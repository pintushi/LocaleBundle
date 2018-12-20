<?php

declare(strict_types=1);

namespace Pintushi\Bundle\CustomerBundle\Context;

use Pintushi\Bundle\CustomerBundle\Entity\CustomerInterface;

interface CustomerContextInterface
{
    /**
     * @return CustomerInterface|null
     */
    public function getCustomer(): ?CustomerInterface;
}
