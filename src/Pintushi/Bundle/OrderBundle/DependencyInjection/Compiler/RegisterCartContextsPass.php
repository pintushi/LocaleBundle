<?php

namespace Pintushi\Bundle\OrderBundle\DependencyInjection\Compiler;

use Pintushi\Bundle\CoreBundle\DependencyInjection\Compiler\PrioritizedCompositeServicePass;

final class RegisterCartContextsPass extends PrioritizedCompositeServicePass
{
    public function __construct()
    {
        parent::__construct(
            'pintushi.context.cart',
            'pintushi.context.cart.composite',
            'pintushi.context.cart',
            'addContext'
        );
    }
}
