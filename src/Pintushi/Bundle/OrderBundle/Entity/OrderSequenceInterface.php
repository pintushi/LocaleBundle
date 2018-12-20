<?php

namespace Pintushi\Bundle\OrderBundle\Entity;

interface OrderSequenceInterface
{
    public function getIndex(): int;

    public function incrementIndex(): void;
}
