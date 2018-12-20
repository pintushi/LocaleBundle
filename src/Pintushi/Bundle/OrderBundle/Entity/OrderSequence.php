<?php

namespace Pintushi\Bundle\OrderBundle\Entity;

class OrderSequence implements OrderSequenceInterface
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var int
     */
    protected $index = 0;

    /**
     * {@inheritdoc}
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    public function incrementIndex(): void
    {
        ++$this->index;
    }
}
