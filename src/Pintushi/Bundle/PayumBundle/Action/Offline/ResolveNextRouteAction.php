<?php

declare(strict_types=1);

namespace Pintushi\Bundle\PayumBundle\Action\Offline;

use Payum\Core\Action\ActionInterface;
use Pintushi\Bundle\PayumBundle\Request\ResolveNextRoute;
use Pintushi\Bundle\PayumBundle\Entity\PaymentInterface;

final class ResolveNextRouteAction implements ActionInterface
{
    /**
     * {@inheritdoc}
     *
     * @param ResolveNextRoute $request
     */
    public function execute($request): void
    {
        $request->setRouteName('sylius_shop_order_thank_you');
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request): bool
    {
        return
            $request instanceof ResolveNextRoute &&
            $request->getFirstModel() instanceof PaymentInterface
        ;
    }
}
