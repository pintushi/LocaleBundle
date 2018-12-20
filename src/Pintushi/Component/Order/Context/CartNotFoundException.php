<?php


namespace Pintushi\Component\Order\Context;

class CartNotFoundException extends \RuntimeException
{
    /**
     * {@inheritdoc}
     */
    public function __construct($message = null, \Exception $previousException = null)
    {
        parent::__construct($message ?: 'Sylius was not able to figure out the current cart.', 0, $previousException);
    }
}
