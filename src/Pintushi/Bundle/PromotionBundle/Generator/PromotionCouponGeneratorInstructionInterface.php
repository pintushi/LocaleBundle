<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Generator;

interface PromotionCouponGeneratorInstructionInterface
{
    /**
     * @return int|null
     */
    public function getAmount(): ?int;

    /**
     * @param int|null $amount
     */
    public function setAmount(?int $amount): void;

    /**
     * @return int|null
     */
    public function getCodeLength(): ?int;

    /**
     * @param int|null $codeLength
     */
    public function setCodeLength(?int $codeLength): void;

    /**
     * @return \DateTimeInterface|null
     */
    public function getExpiresAt(): ?\DateTimeInterface;

    /**
     * @param \DateTimeInterface $expiresAt
     */
    public function setExpiresAt(?\DateTimeInterface $expiresAt): void;

    /**
     * @return int|null
     */
    public function getUsageLimit(): ?int;

    /**
     * @param int $usageLimit
     */
    public function setUsageLimit(int $usageLimit): void;
}
