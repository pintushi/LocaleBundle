<?php


declare(strict_types=1);

namespace Pintushi\Bundle\PayumBundle\Action;

use Payum\Core\Action\ActionInterface;
use Pintushi\Bundle\PayumBundle\Request\ResolveNextRoute;
use Pintushi\Bundle\PaymentBundle\Entity\PaymentInterface;

final class ResolveNextRouteAction implements ActionInterface
{
    /**
     * {@inheritdoc}
     *
     * @param ResolveNextRoute $request
     */
    public function execute($request): void
    {
        /** @var PaymentInterface $payment */
        $payment = $request->getFirstModel();

        if (
            $payment->getState() === PaymentInterface::STATE_COMPLETED ||
            $payment->getState() === PaymentInterface::STATE_AUTHORIZED
        ) {
            $request->setRouteName(
                'sylius_shop_order_thank_you'
            );

            return;
        }

        $request->setRouteName('sylius_shop_order_show');
        $request->setRouteParameters(['tokenValue' => $payment->getOrder()->getTokenValue()]);
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
