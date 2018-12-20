<?php

declare(strict_types=1);

namespace Pintushi\Bundle\ReviewBundle\Factory;

use Pintushi\Bundle\ReviewBundle\Entity\ReviewableInterface;
use Pintushi\Bundle\ReviewBundle\Entity\ReviewerInterface;
use Pintushi\Bundle\ReviewBundle\Entity\ReviewInterface;

interface ReviewFactoryInterface extends FactoryInterface
{
    /**
     * @param ReviewableInterface $subject
     *
     * @return ReviewInterface
     */
    public function createForSubject(ReviewableInterface $subject): ReviewInterface;

    /**
     * @param ReviewableInterface $subject
     * @param ReviewerInterface|null $reviewer
     *
     * @return ReviewInterface
     */
    public function createForSubjectWithReviewer(ReviewableInterface $subject, ?ReviewerInterface $reviewer): ReviewInterface;
}
