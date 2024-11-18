<?php

declare(strict_types=1);

namespace App\Component\User\Application\CommandHandler;

use App\Common\Application\Bus\EventBusInterface;
use App\Common\Application\Command\CommandHandlerInterface;
use App\Component\User\Application\Command\ChangeUserPasswordCommand;
use App\Component\User\Application\Exception\UserNotFoundException;
use App\Component\User\Domain\Exception\WrongOldPasswordException;
use App\Component\User\Domain\Presenter\UserPresenterInterface;
use App\Component\User\Domain\Storage\UserStorageInterface;
use App\Component\User\Domain\ValueObject\HashedPassword;

final readonly class ChangeUserPasswordCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserPresenterInterface $userPresenter,
        private UserStorageInterface $userStorage,
        private EventBusInterface $eventBus,
    ) {
    }

    /**
     * @throws UserNotFoundException
     */
    public function __invoke(ChangeUserPasswordCommand $command): void
    {
        $user = $this->userPresenter->findById($command->userId);

        if (null === $user) {
            throw new UserNotFoundException();
        }

        if (!$user->getPassword()->equals($command->oldPassword)) {
            throw new WrongOldPasswordException();
        }

        $user->changePassword(HashedPassword::createFormPlain($command->newPassword));
        $this->userStorage->store($user);
        $this->eventBus->dispatchMany($user->pullEvents());
    }
}
