<?php

namespace Pintushi\Bundle\ShippingBundle\Exception;

class UnresolvedDefaultShippingMethodException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Default shipping method could not be resolved!');
    }
}
