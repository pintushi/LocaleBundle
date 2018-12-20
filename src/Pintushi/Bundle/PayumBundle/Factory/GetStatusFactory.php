<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PayumBundle\Factory;

use Payum\Core\Request\GetStatusInterface;
use Pintushi\Bundle\PayumBundle\Request\GetStatus;

final class GetStatusFactory implements GetStatusFactoryInterface
{
    public function createNewWithModel($model): GetStatusInterface
    {
        return new GetStatus($model);
    }
}
