<?php

declare(strict_types=1);

namespace Pintushi\Bundle\OrderBundle\TokenAssigner;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\OrderBundle\Generator\RandomnessGeneratorInterface;

final class UniqueIdBasedOrderTokenAssigner implements OrderTokenAssignerInterface
{
    /**
     * @var RandomnessGeneratorInterface
     */
    private $generator;

    /**
     * @param RandomnessGeneratorInterface $generator
     */
    public function __construct(RandomnessGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    /**
     * {@inheritdoc}
     */
    public function assignTokenValue(OrderInterface $order): void
    {
        $order->setTokenValue($this->generator->generateUriSafeString(10));
    }

    /**
     * {@inheritdoc}
     */
    public function assignTokenValueIfNotSet(OrderInterface $order): void
    {
        if (null === $order->getTokenValue()) {
            $this->assignTokenValue($order);
        }
    }
}
