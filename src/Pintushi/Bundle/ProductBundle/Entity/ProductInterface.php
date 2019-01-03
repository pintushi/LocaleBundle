<?php

namespace Pintushi\Bundle\ProductBundle\Entity;

use Pintushi\Bundle\UserBundle\Entity\TimestampableInterface;
use Pintushi\Bundle\UserBundle\Entity\ToggleableInterface;
use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Pintushi\Bundle\InventoryBundle\Entity\StockableInterface;

interface ProductInterface extends ToggleableInterface, TimestampableInterface, OrganizationAwareInterface, StockableInterface
{
    public function getName(): ?string;

    public function setName($name);

    public function getDescription(): ?string;

    public function setDescription($description);
}
