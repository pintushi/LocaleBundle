<?php

declare(strict_types=1);

namespace Pintushi\Bundle\CoreBundle\EventListener;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use League\Tactician\CommandBus;

final class WriteListener
{
    private $dataPersister;

    private $commandBus;

    public function __construct(DataPersisterInterface $dataPersister, CommandBus $commandBus)
    {
        $this->dataPersister = $dataPersister;
        $this->commandBus = $commandBus;
    }

    /**
     * Persists, updates or delete data return by the controller if applicable.
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();
        if ($request->isMethodSafe(false) || !$request->attributes->has('_api_resource_class')) {
            return;
        }

        $controllerResult = $event->getControllerResult();

        if ($request->attributes->getBoolean('_api_command_bus', false)) {
            $result = $this->commandBus->handle($controllerResult);

            $event->setControllerResult($result ?? $controllerResult);

            return;
        }

        if ($request->attributes->getBoolean('_api_persist', true)) {
            if (!$this->dataPersister->supports($controllerResult)) {
                return;
            }

            switch ($request->getMethod()) {
                case 'PUT':
                case 'PATCH':
                case 'POST':
                    $persistResult = $this->dataPersister->persist($controllerResult);

                    $event->setControllerResult($persistResult ?? $controllerResult);

                    break;
                case 'DELETE':
                    $this->dataPersister->remove($controllerResult);
                    $event->setControllerResult(null);
                    break;
            }
        }
    }
}
