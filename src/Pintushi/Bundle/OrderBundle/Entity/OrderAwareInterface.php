<?php

namespace Pintushi\Bundle\OrderBundle\Entity;

interface OrderAwareInterface
{
    public function getOrder(): ?OrderInterface;

    public function setOrder(?OrderInterface $order);
}
