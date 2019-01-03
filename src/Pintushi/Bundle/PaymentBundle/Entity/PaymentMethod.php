<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\Entity;

use Pintushi\Bundle\UserBundle\Entity\TimestampableTrait;
use Pintushi\Bundle\UserBundle\Entity\ToggleableTrait;
use Pintushi\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Pintushi\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;
use Pintushi\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Payum\Core\Model\GatewayConfigInterface;

/**
 * @Config(
 *  defaultValues={
 *     "security"={
 *          "type"="ACL",
 *          "group_name"="",
 *          "category"="payum",
 *      },
 *    "ownership"={
 *           "owner_type"="ORGANIZATION",
 *           "owner_field_name"="organization",
 *           "owner_column_name"="organization_id",
 *     },
 *  }
 * )
 */
class PaymentMethod implements PaymentMethodInterface, OrganizationAwareInterface
{
    use TimestampableTrait, ToggleableTrait, OrganizationAwareTrait;

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     */
    protected $environment;

    /**
     * @var int
     */
    protected $position;

    protected $name;

    protected $instructions;

    protected $description;

    /**
     * @var GatewayConfigInterface
     */
    protected $gatewayConfig;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getEnvironment(): ?string
    {
        return $this->environment;
    }

    /**
     * {@inheritdoc}
     */
    public function setEnvironment(?string $environment): void
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * @param mixed $instructions
     *
     * @return self
     */
    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setGatewayConfig(?GatewayConfigInterface $gatewayConfig)
    {
        $this->gatewayConfig = $gatewayConfig;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getGatewayConfig(): ?GatewayConfigInterface
    {
        return $this->gatewayConfig;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
