<?php

namespace Pintushi\Bundle\OrderBundle\DependencyInjection\Compiler;

use Pintushi\Bundle\CoreBundle\DependencyInjection\Compiler\PrioritizedCompositeServicePass;

final class RegisterProcessorsPass extends PrioritizedCompositeServicePass
{
    public function __construct()
    {
        parent::__construct(
            'pintushi.order_processing.order_processor',
            'pintushi.order_processing.order_processor.composite',
            'pintushi.order_processor',
            'addProcessor'
        );
    }
}
