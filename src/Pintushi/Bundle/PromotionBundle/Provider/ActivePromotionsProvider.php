<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Provider;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;
use Pintushi\Bundle\PromotionBundle\Repository\PromotionRepositoryInterface;

final class ActivePromotionsProvider implements PreQualifiedPromotionsProviderInterface
{
    /**
     * @var PromotionRepositoryInterface
     */
    private $promotionRepository;

    /**
     * @param PromotionRepositoryInterface $promotionRepository
     */
    public function __construct(PromotionRepositoryInterface $promotionRepository)
    {
        $this->promotionRepository = $promotionRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getPromotions(PromotionSubjectInterface $subject): array
    {
        return $this->promotionRepository->findActive();
    }
}
