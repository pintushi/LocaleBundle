<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PayumBundle\Factory;

use Pintushi\Bundle\PayumBundle\Request\ResolveNextRoute;
use Pintushi\Bundle\PayumBundle\Request\ResolveNextRouteInterface;

final class ResolveNextRouteFactory implements ResolveNextRouteFactoryInterface
{
    public function createNewWithModel($model): ResolveNextRouteInterface
    {
        return new ResolveNextRoute($model);
    }
}
