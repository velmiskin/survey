<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Bus;

use App\Common\Application\Bus\CommandBusInterface;
use App\Common\Application\Command\CommandInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class MessengerCommandBus implements CommandBusInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function handle(CommandInterface $command): void
    {
        $this->messageBus->dispatch($command);
    }
}
