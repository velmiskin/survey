<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Bus;

use App\Common\Application\Bus\CommandBusInterface;
use App\Common\Application\Command\CommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerCommandBus implements CommandBusInterface
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
    }

    public function handle(CommandInterface $command): void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch (\Exception) {
            // @todo log error
        }
    }
}
