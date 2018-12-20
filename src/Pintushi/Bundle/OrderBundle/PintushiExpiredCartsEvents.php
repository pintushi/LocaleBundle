<?php

namespace Pintushi\Bundle\OrderBundle;

final class PintushiExpiredCartsEvents
{
    const PRE_REMOVE = 'pintushi.carts.pre_remove';
    const POST_REMOVE = 'pintushi.carts.post_remove';

    private function __construct()
    {
    }
}
