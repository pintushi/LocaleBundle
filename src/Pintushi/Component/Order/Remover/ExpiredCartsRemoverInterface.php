<?php

namespace Pintushi\Component\Order\Remover;

interface ExpiredCartsRemoverInterface
{
    public function remove(): void;
}
