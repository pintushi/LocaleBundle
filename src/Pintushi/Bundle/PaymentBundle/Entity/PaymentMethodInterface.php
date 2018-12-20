<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\Entity;

use Pintushi\Bundle\UserBundle\Entity\TimestampableInterface;
use Pintushi\Bundle\UserBundle\Entity\ToggleableInterface;

interface PaymentMethodInterface extends
    TimestampableInterface,
    ToggleableInterface
{
    /**
     * @return string|null
     */
    public function getEnvironment(): ?string;

    /**
     * @param string|null $environment
     */
    public function setEnvironment(?string $environment): void;

    /**
     * @return int|null
     */
    public function getPosition(): ?int;

    /**
     * @param int|null $position
     */
    public function setPosition(?int $position): void;
}
