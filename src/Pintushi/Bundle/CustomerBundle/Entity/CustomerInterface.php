<?php

declare(strict_types=1);

namespace Pintushi\Bundle\CustomerBundle\Entity;

use Pintushi\Bundle\UserBundle\Entity\TimestampableInterface;
use Pintushi\Bundle\UserBundle\Entity\ToggleableInterface;
use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;
use Pintushi\Bundle\ReviewBundle\Entity\ReviewerInterface;

interface CustomerInterface extends TimestampableInterface, BaseUserInterface, ToggleableInterface, ReviewerInterface
{
    /**
     * @return string
     */
    public function getFullName(): string;


    /**
     * @return CustomerGroupInterface|null
     */
    public function getGroup(): ?CustomerGroupInterface;

    /**
     * @param CustomerGroupInterface|null $group
     */
    public function setGroup(?CustomerGroupInterface $group): void;
}
