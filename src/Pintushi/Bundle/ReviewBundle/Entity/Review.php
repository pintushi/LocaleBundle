<?php

declare(strict_types=1);

namespace Pintushi\Bundle\ReviewBundle\Entity;

use Pintushi\Bundle\UserBundle\Entity\TimestampableTrait;
use Doctrine\ORM\Event\LifecycleEventArgs;

abstract class Review implements ReviewInterface
{
    use TimestampableTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $rating;

    /**
     * @var string
     */
    protected $comment;

    /**
     * @var ReviewerInterface
     */
    protected $author;

    protected $name;

    /**
     * @var string
     */
    protected $status = ReviewInterface::STATUS_NEW;

    /**
     * @var ReviewableInterface
     */
    protected $reviewSubject;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * {@inheritdoc}
     */
    public function setRating(?int $rating): void
    {
        $this->rating = $rating;
    }

    /**
     * {@inheritdoc}
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * {@inheritdoc}
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthor(): ?ReviewerInterface
    {
        return $this->author;
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthor(?ReviewerInterface $author): void
    {
        $this->author = $author;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    /**
     * {@inheritdoc}
     */
    public function getReviewSubject(): ?ReviewableInterface
    {
        return $this->reviewSubject;
    }

    /**
     * {@inheritdoc}
     */
    public function setReviewSubject(?ReviewableInterface $reviewSubject): void
    {
        $this->reviewSubject = $reviewSubject;
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $this->setName($this->getReviewSubject()->getName());
    }

    public function preUpdate(LifecycleEventArgs $event)
    {
        $this->setName($this->getReviewSubject()->getName());
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
}
