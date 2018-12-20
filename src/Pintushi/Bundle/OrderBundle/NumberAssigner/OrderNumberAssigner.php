<?php


namespace Pintushi\Bundle\OrderBundle\NumberAssigner;

use Pintushi\Bundle\OrderBundle\NumberGenerator\OrderNumberGeneratorInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;

final class OrderNumberAssigner implements OrderNumberAssignerInterface
{
    /**
     * @var OrderNumberGeneratorInterface
     */
    private $numberGenerator;

    public function __construct(OrderNumberGeneratorInterface $numberGenerator)
    {
        $this->numberGenerator = $numberGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function assignNumber(OrderInterface $order): void
    {
        if (null !== $order->getNumber()) {
            return;
        }

        $order->setNumber($this->numberGenerator->generate($order));
    }
}
