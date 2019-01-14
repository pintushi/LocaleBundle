<?php

namespace Pintushi\Bundle\ShippingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Pintushi\Bundle\UserBundle\Entity\TimestampableTrait;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;

class Shipment implements ShipmentInterface
{
    use TimestampableTrait;

    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     */
    protected $state = ShipmentInterface::STATE_CART;

    /**
     * @var ShippingMethodInterface
     */
    protected $method;

    /**
     * @var string
     */
    protected $tracking;

    /**
     * @var BaseOrderInterface
     */
    protected $order;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * {@inheritdoc}
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod(): ?ShippingMethodInterface
    {
        return $this->method;
    }

    /**
     * {@inheritdoc}
     */
    public function setMethod(ShippingMethodInterface $method = null): void
    {
        $this->method = $method;
    }

    /**
     * {@inheritdoc}
     */
    public function getTracking(): ?string
    {
        return $this->tracking;
    }

    /**
     * {@inheritdoc}
     */
    public function setTracking(string $tracking): void
    {
        $this->tracking = $tracking;
    }

    /**
     * {@inheritdoc}
     */
    public function isTracked(): bool
    {
        return null !== $this->tracking;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder(): ?OrderInterface
    {
        return $this->order;
    }

    /**
     * {@inheritdoc}
     */
    public function setOrder(?OrderInterface $order)
    {
        $this->order = $order;

        return $this;
    }
}
