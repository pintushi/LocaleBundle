<?php

namespace Pintushi\Bundle\PromotionBundle\Checker\Eligibility;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionCouponInterface as CorePromotionCouponInterface;
use Pintushi\Component\Order\Repository\OrderRepositoryInterface;
use Pintushi\Bundle\PromotionBundle\Checker\Eligibility\PromotionCouponEligibilityCheckerInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionCouponInterface;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;

final class PromotionCouponPerCustomerUsageLimitEligibilityChecker implements PromotionCouponEligibilityCheckerInterface
{
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
    public function isEligible(PromotionSubjectInterface $promotionSubject, PromotionCouponInterface $promotionCoupon): bool
    {
        if (!$promotionSubject instanceof OrderInterface) {
            return true;
        }

        if (!$promotionCoupon instanceof CorePromotionCouponInterface) {
            return true;
        }

        $perCustomerUsageLimit = $promotionCoupon->getPerCustomerUsageLimit();
        if ($perCustomerUsageLimit === null) {
            return true;
        }

        $customer = $promotionSubject->getCustomer();
        if ($customer === null || $customer->getId() === null) {
            return true;
        }

        $placedOrdersNumber = $this->orderRepository->countByCustomerAndCoupon($customer, $promotionCoupon);

        return $placedOrdersNumber < $perCustomerUsageLimit;
    }
}
