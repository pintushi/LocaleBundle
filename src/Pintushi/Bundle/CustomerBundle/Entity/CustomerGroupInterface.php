<?php

declare(strict_types=1);

namespace Pintushi\Bundle\CustomerBundle\Entity;

interface CustomerGroupInterface
{
    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void;
}
