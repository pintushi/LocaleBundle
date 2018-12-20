<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PaymentBundle;

final class PaymentTransitions
{
    public const GRAPH = 'pintushi_payment';

    public const TRANSITION_CREATE = 'create';
    public const TRANSITION_PROCESS = 'process';
    public const TRANSITION_COMPLETE = 'complete';
    public const TRANSITION_FAIL = 'fail';
    public const TRANSITION_CANCEL = 'cancel';
    public const TRANSITION_REFUND = 'refund';
    public const TRANSITION_VOID = 'void';

    private function __construct()
    {
    }
}
