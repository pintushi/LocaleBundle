<?php

namespace Pintushi\Bundle\PromotionBundle\Checker\Rule;

use Pintushi\Component\Core\Model\CustomerInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\PromotionBundle\Checker\Rule\RuleCheckerInterface;
use Pintushi\Bundle\PromotionBundle\Exception\UnsupportedTypeException;
use Pintushi\Bundle\PromotionBundle\Entity\PromotionSubjectInterface;

class CustomerGroupRuleChecker implements RuleCheckerInterface
{
    const TYPE = 'customer_group';

    /**
     * {@inheritdoc}
     */
    public function isEligible(PromotionSubjectInterface $subject, array $configuration): bool
    {
        if (!$subject instanceof OrderInterface) {
            throw new UnsupportedTypeException($subject, OrderInterface::class);
        }

        if (null === $customer = $subject->getCustomer()) {
            return false;
        }

        if (!$customer instanceof CustomerInterface) {
            return false;
        }

        if (null === $customer->getGroup()) {
            return false;
        }

        return $configuration['group_id'] === $customer->getGroup()->getId();
    }
}
