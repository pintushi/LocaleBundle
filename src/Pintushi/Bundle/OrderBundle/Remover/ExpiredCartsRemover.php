<?php

namespace Pintushi\Bundle\OrderBundle\Remover;

use Doctrine\Common\Persistence\ObjectManager;
use Pintushi\Bundle\OrderBundle\PintushiExpiredCartsEvents;
use Pintushi\Component\Order\Remover\ExpiredCartsRemoverInterface;
use Pintushi\Component\Order\Repository\OrderRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
final class ExpiredCartsRemover implements ExpiredCartsRemoverInterface
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var ObjectManager
     */
    private $orderManager;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var string
     */
    private $expirationPeriod;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ObjectManager $orderManager,
        EventDispatcherInterface $eventDispatcher,
        string $expirationPeriod
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderManager = $orderManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->expirationPeriod = $expirationPeriod;
    }

    public function remove(): void
    {
        $expiredCarts = $this->orderRepository->findCartsNotModifiedSince(new \DateTime('-'.$this->expirationPeriod));

        $this->eventDispatcher->dispatch(SyliusExpiredCartsEvents::PRE_REMOVE, new GenericEvent($expiredCarts));

        foreach ($expiredCarts as $expiredCart) {
            $this->orderManager->remove($expiredCart);
        }

        $this->orderManager->flush();

        $this->eventDispatcher->dispatch(SyliusExpiredCartsEvents::POST_REMOVE, new GenericEvent($expiredCarts));
    }
}
