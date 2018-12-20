<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PayumBundle\Entity;

use Payum\Core\Security\TokenInterface;
use Payum\Core\Security\Util\Random;

class PaymentSecurityToken implements TokenInterface
{
    /**
     * @var string
     */
    protected $hash;

    /**
     * @var mixed
     */
    protected $details;

    /**
     * @var string
     */
    protected $afterUrl;

    /**
     * @var string
     */
    protected $targetUrl;

    /**
     * @var string
     */
    protected $gatewayName;

    public function __construct()
    {
        $this->hash = Random::generateToken();
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return $this->hash;
    }

    /**
     * {@inheritdoc}
     */
    public function setDetails($details): void
    {
        $this->details = $details;
    }

    /**
     * {@inheritdoc}
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * {@inheritdoc}
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * {@inheritdoc}
     */
    public function setHash($hash): void
    {
        $this->hash = $hash;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetUrl(): string
    {
        return $this->targetUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function setTargetUrl($targetUrl): void
    {
        $this->targetUrl = $targetUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getAfterUrl(): ?string
    {
        return $this->afterUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function setAfterUrl($afterUrl): void
    {
        $this->afterUrl = $afterUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getGatewayName(): string
    {
        return $this->gatewayName;
    }

    /**
     * {@inheritdoc}
     */
    public function setGatewayName($gatewayName): void
    {
        $this->gatewayName = $gatewayName;
    }
}
