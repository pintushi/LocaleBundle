<?php

namespace Pintushi\Bundle\OrderBundle\NumberGenerator;

use Pintushi\Bundle\OrderBundle\Entity\OrderInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderSequenceInterface;
use Pintushi\Bundle\OrderBundle\Entity\OrderSequence;
use Pintushi\Bundle\OrderBundle\Repository\OrderSequenceRepository;

final class SequentialOrderNumberGenerator implements OrderNumberGeneratorInterface
{
    /**
     * @var OrderSequenceRepository
     */
    private $sequenceRepository;

    /**
     * @var int
     */
    private $startNumber;

    /**
     * @var int
     */
    private $numberLength;

    public function __construct(
        OrderSequenceRepository $sequenceRepository,
        int $startNumber = 1,
        int $numberLength = 9
    ) {
        $this->sequenceRepository = $sequenceRepository;
        $this->startNumber = $startNumber;
        $this->numberLength = $numberLength;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(OrderInterface $order): string
    {
        $sequence = $this->getSequence();

        $number = $this->generateNumber($sequence->getIndex());
        $sequence->incrementIndex();

        return $number;
    }

    private function generateNumber(int $index): string
    {
        $number = $this->startNumber + $index;

        return str_pad($number, $this->numberLength, 0, STR_PAD_LEFT);
    }

    private function getSequence(): OrderSequenceInterface
    {
        /** @var OrderSequenceInterface $sequence */
        $sequence = $this->sequenceRepository->findOneBy([]);

        if (null === $sequence) {
            $sequence = new OrderSequence();
            $this->sequenceRepository->add($sequence);
        }

        return $sequence;
    }
}
