<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PayumBundle\Factory;

use Pintushi\Bundle\PayumBundle\Request\ResolveNextRouteInterface;

interface ResolveNextRouteFactoryInterface
{
    public function createNewWithModel($model): ResolveNextRouteInterface;
}
