<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PromotionBundle\Action;

use Pintushi\Bundle\PromotionBundle\Entity\PromotionInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;

final class PromotionApplicator implements PromotionApplicatorInterface
{
    /**
     * @var ServiceRegistryInterface
     */
    private $registry;

    /**
     * @param ServiceRegistryInterface $registry
     */
    public function __construct(ServiceRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(PromotionSubjectInterface $subject, PromotionInterface $promotion): void
    {
        $action=$promotion->getAction();

        if (!$action) {
            return;
        }
        $applyPromotion = false;
        $result = $this->getActionCommandByType($action->getType())->execute($subject, $action->getConfiguration(), $promotion);
        $applyPromotion |= $result;

        if ($applyPromotion) {
            $subject->addPromotion($promotion);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function revert(PromotionSubjectInterface $subject, PromotionInterface $promotion): void
    {
        $action=$promotion->getAction();
        if (!$action) {
            return;
        }
        $this->getActionCommandByType($action->getType())->revert($subject, $action->getConfiguration(), $promotion);

        $subject->removePromotion($promotion);
    }

    /**
     * @param string $type
     *
     * @return PromotionActionCommandInterface
     */
    private function getActionCommandByType(string $type): PromotionActionCommandInterface
    {
        return $this->registry->get($type);
    }
}
