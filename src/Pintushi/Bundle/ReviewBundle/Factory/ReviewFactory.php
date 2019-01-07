<?php

declare(strict_types=1);

namespace Pintushi\Bundle\ReviewBundle\Factory;

use Pintushi\Bundle\ReviewBundle\Entity\ReviewableInterface;
use Pintushi\Bundle\ReviewBundle\Entity\ReviewerInterface;
use Pintushi\Bundle\ReviewBundle\Entity\ReviewInterface;
use Videni\Bundle\RestBundle\Factory\FactoryInterface;

class ReviewFactory implements ReviewFactoryInterface, FactoryInterface
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
        return $this->factory->createNew();
    }

    /**
     * {@inheritdoc}
     */
    public function createForSubject(ReviewableInterface $subject): ReviewInterface
    {
        /** @var ReviewInterface $review */
        $review = $this->factory->createNew();
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
