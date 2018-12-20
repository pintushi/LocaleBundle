<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Generator;

final class PromotionCouponGeneratorInstruction implements PromotionCouponGeneratorInstructionInterface
{
    /**
     * @var int
     */
    private $amount = 5;

    /**
     * @var int
     */
    private $codeLength = 6;

    /**
     * @var \DateTimeInterface
     */
    private $expiresAt;

    /**
     * @var int
     */
    private $usageLimit;

    /**
     * {@inheritdoc}
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * {@inheritdoc}
     */
    public function setAmount(?int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * {@inheritdoc}
     */
    public function getCodeLength(): ?int
    {
        return $this->codeLength;
    }

    /**
     * {@inheritdoc}
     */
    public function setCodeLength(?int $codeLength): void
    {
        $this->codeLength = $codeLength;
    }

    /**
     * {@inheritdoc}
     */
    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setExpiresAt(?\DateTimeInterface $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsageLimit(): ?int
    {
        return $this->usageLimit;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsageLimit(int $usageLimit): void
    {
        $this->usageLimit = $usageLimit;
    }
}
