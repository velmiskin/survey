<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Bus;

use App\Common\Application\Bus\EventBusInterface;
use App\Common\Domain\EventInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerEventBus implements EventBusInterface
{
    public function __construct(
        private readonly MessageBusInterface $eventBus,
    ) {
    }

    /**
     * @param EventInterface[] $events
     *
     * @throws ExceptionInterface
     */
    public function dispatchMany(array $events): void
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    /**
     * @throws ExceptionInterface
     */
    public function dispatch(EventInterface $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
