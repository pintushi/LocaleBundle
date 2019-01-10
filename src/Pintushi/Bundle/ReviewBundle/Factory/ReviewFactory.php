<?php

declare(strict_types=1);

namespace Pintushi\Bundle\ReviewBundle\Factory;

use Pintushi\Bundle\ReviewBundle\Entity\ReviewableInterface;
use Pintushi\Bundle\ReviewBundle\Entity\ReviewerInterface;
use Pintushi\Bundle\ReviewBundle\Entity\ReviewInterface;

class ReviewFactory implements ReviewFactoryInterface
{
    /**
     * @var FactoryInterface
     */
    private $class;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function createNew(): ReviewInterface
    {
        return new $this->class();
    }

    /**
     * {@inheritdoc}
     */
    public function createForSubject(ReviewableInterface $subject): ReviewInterface
    {
        /** @var ReviewInterface $review */
        $review = $this->createNew();
        $review->setReviewSubject($subject);

        return $review;
    }

    /**
     * {@inheritdoc}
     */
    public function createForSubjectWithReviewer(ReviewableInterface $subject, ?ReviewerInterface $reviewer): ReviewInterface
    {
        /** @var ReviewInterface $review */
        $review = $this->createForSubject($subject);
        $review->setAuthor($reviewer);

        return $review;
    }
}
