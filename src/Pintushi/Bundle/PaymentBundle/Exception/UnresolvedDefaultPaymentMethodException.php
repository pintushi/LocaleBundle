<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle\Exception;

class UnresolvedDefaultPaymentMethodException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Default payment method could not be resolved!');
    }
}
