<?php

namespace Pintushi\Component\Core\Factory;

use Pintushi\Bundle\PromotionBundle\Action\FixedDiscountPromotionActionCommand;
use Pintushi\Bundle\PromotionBundle\Action\PercentageDiscountPromotionActionCommand;
use Pintushi\Bundle\PromotionBundle\Action\ShippingPercentageDiscountPromotionActionCommand;
use Pintushi\Bundle\PromotionBundle\Action\UnitFixedDiscountPromotionActionCommand;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionActionInterface;
use Pintushi\Bundle\PromotionBundle\Entity\Action;

class PromotionActionFactory implements PromotionActionFactoryInterface
{

    /**
     * {@inheritdoc}
     */
    public function createNew()
    {
        return new PromotionAction();
    }

    /**
     * {@inheritdoc}
     */
    public function createFixedDiscount(int $amount, string $channelId): PromotionActionInterface
    {
        return $this->createAction(
            FixedDiscountPromotionActionCommand::TYPE,
            [
                $channelId => ['amount' => $amount],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function createPercentageDiscount(float $percentage): PromotionActionInterface
    {
        return $this->createAction(
            PercentageDiscountPromotionActionCommand::TYPE,
            [
                'percentage' => $percentage,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function createShippingPercentageDiscount(float $percentage): PromotionActionInterface
    {
        return $this->createAction(
            ShippingPercentageDiscountPromotionActionCommand::TYPE,
            [
                'percentage' => $percentage,
            ]
        );
    }

    /**
     * @param string $type
     * @param array $configuration
     *
     * @return PromotionActionInterface
     */
    private function createAction(string $type, array $configuration): PromotionActionInterface
    {
        /** @var PromotionActionInterface $action */
        $action = $this->createNew();
        $action->setType($type);
        $action->setConfiguration($configuration);

        return $action;
    }
}
