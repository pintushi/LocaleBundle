<?php

namespace Pintushi\Bundle\PromotionBundle\Checker\Rule;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Component\Order\Repository\OrderRepositoryInterface;
use Pintushi\Bundle\PromotionBundle\Checker\Rule\RuleCheckerInterface;
use Pintushi\Bundle\PromotionBundle\Exception\UnsupportedTypeException;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;

final class NthOrderRuleChecker implements RuleCheckerInterface
{
    const TYPE = 'nth_order';

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function isEligible(PromotionSubjectInterface $subject, array $configuration): bool
    {
        if (!$subject instanceof OrderInterface) {
            throw new UnsupportedTypeException($subject, OrderInterface::class);
        }

        if (!isset($configuration['nth']) || !is_int($configuration['nth'])) {
            return false;
        }

        $customer = $subject->getCustomer();
        if (null === $customer) {
            return false;
        }

        //eligible if it is first order of guest and the promotion is on first order
        if (null === $customer->getId()) {
            return 1 === $configuration['nth'];
        }

        return $this->orderRepository->countByCustomer($customer) === ($configuration['nth'] - 1);
    }
}
