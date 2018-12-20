<?php

namespace Pintushi\Bundle\MaintenanceBundle\Entity;

use Pintushi\Bundle\TaxonomyBundle\Entity\TaxonInterface;
use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Pintushi\Bundle\UserBundle\Entity\TimestampableInterface;

interface ServiceInterface extends OrganizationAwareInterface, TimestampableInterface
{
    /**
     * @return mixed
     */
    public function getPrice();

    /**
     * @param mixed $price
     */
    public function setPrice($price): void;

    /**
     * @return mixed
     */
    public function getPriority();

    /**
     * @param mixed $priority
     */
    public function setPriority($priority): void;

    /**
     * @return mixed
     */
    public function getEnabled();

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled): void;

    /**
     * @return mixed
     */
    public function getDescription();

    /**
     * @param mixed $description
     */
    public function setDescription($description): void;

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param mixed $name
     */
    public function setName($name): void;

    public function getGroup(): ?ServiceGroupInterface;

    public function setGroup(?ServiceGroupInterface $group): void;
}
