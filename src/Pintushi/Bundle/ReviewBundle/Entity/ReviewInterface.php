<?php

declare(strict_types=1);

namespace Pintushi\Bundle\ReviewBundle\Entity;

use Pintushi\Bundle\UserBundle\Entity\TimestampableInterface;

interface ReviewInterface extends TimestampableInterface
{
    public const STATUS_NEW = 'new';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';

    /**
     * @return int|null
     */
    public function getRating(): ?int;

    /**
     * @param int|null $rating
     */
    public function setRating(?int $rating): void;

    /**
     * @return string|null
     */
    public function getComment(): ?string;

    /**
     * @param string|null $comment
     */
    public function setComment(?string $comment): void;

    /**
     * @return ReviewerInterface|null
     */
    public function getAuthor(): ?ReviewerInterface;

    /**
     * @param ReviewerInterface|null $author
     */
    public function setAuthor(?ReviewerInterface $author): void;

    /**
     * @return string|null
     */
    public function getStatus(): ?string;

    /**
     * @param string|null $status
     */
    public function setStatus(?string $status): void;

    /**
     * @return ReviewableInterface|null
     */
    public function getReviewSubject(): ?ReviewableInterface;

    /**
     * @param ReviewableInterface|null $reviewSubject
     */
    public function setReviewSubject(?ReviewableInterface $reviewSubject): void;
}
