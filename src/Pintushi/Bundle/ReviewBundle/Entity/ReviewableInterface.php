<?php

declare(strict_types=1);

namespace Pintushi\Bundle\ReviewBundle\Entity;

interface ReviewableInterface
{
    public function getName(): ?string;
}
