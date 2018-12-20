<?php


declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Entity;

use Pintushi\Bundle\UserBundle\Entity\TimestampableInterface;

interface PromotionCouponInterface extends TimestampableInterface
{
    /**
     * @return int|null
     */
    public function getUsageLimit(): ?int;

    /**
     * @param int|null $usageLimit
     */
    public function setUsageLimit(?int $usageLimit): void;

    /**
     * @return int
     */
    public function getUsed(): int;

    /**
     * @param int $used
     */
    public function setUsed(int $used): void;

    public function incrementUsed(): void;

    public function decrementUsed(): void;

    /**
     * @return PromotionInterface
     */
    public function getPromotion(): ?PromotionInterface;

    /**
     * @param PromotionInterface|null $promotion
     */
    public function setPromotion(?PromotionInterface $promotion): void;

    /**
     * @return \DateTimeInterface|null
     */
    public function getExpiresAt(): ?\DateTimeInterface;

    /**
     * @param \DateTimeInterface $expiresAt
     */
    public function setExpiresAt(?\DateTimeInterface $expiresAt): void;

    /**
     * @return bool
     */
    public function isValid(): bool;
}
