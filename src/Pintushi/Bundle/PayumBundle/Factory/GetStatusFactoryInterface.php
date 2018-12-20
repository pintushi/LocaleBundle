<?php


declare(strict_types=1);

namespace Pintushi\Bundle\PayumBundle\Factory;

use Payum\Core\Request\GetStatusInterface;

interface GetStatusFactoryInterface
{
    public function createNewWithModel($model): GetStatusInterface;
}
