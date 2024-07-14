<?php

declare(strict_types=1);


namespace App\Component\User\Application\CommandHandler;

use App\Common\Application\Bus\EventBusInterface;
use App\Common\Application\Command\CommandHandlerInterface;
use App\Common\Application\Command\CommandInterface;
use App\Component\User\Application\Command\ChangeUserPasswordCommand;
use App\Component\User\Domain\Presenter\UserPresenterInterface;
use App\Component\User\Domain\Storage\UserStorageInterface;

final readonly class ChangeUserPasswordCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserPresenterInterface $userPresenter,
        private UserStorageInterface $userStorage,
        private EventBusInterface $eventBus,
    ) {
    }

    /**
     * @param ChangeUserPasswordCommand $command
     */
    public function __invoke(CommandInterface $command): void
    {
        // TODO: Implement __invoke() method.
    }

}