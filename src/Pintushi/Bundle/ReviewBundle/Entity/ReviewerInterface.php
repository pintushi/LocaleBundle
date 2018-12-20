<?php

declare(strict_types=1);

namespace Pintushi\Bundle\ReviewBundle\Entity;

interface ReviewerInterface
{
    /**
     * @return string|null
     */
    public function getFirstName();

    /**
     * @param string|null $firstName
     */
    public function setFirstName($firstName);

    /**
     * @return string|null
     */
    public function getLastName();

    /**
     * @param string|null $lastName
     */
    public function setLastName($lastName);
}
