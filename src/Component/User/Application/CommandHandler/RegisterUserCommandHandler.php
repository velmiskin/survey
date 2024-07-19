<?php

declare(strict_types=1);

namespace App\Component\User\Application\CommandHandler;

use App\Common\Application\Command\CommandHandlerInterface;
use App\Component\User\Application\Command\RegisterUserCommand;
use App\Component\User\Domain\Exception\InvalidEmailException;
use App\Component\User\Domain\Exception\InvalidPasswordException;
use App\Component\User\Domain\Factory\UserFactory;
use App\Component\User\Domain\Storage\UserStorageInterface;
use Symfony\Component\Clock\ClockInterface;

final readonly class RegisterUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserStorageInterface $userStorage,
        private UserFactory $userFactory,
        private ClockInterface $clock,
    ) {
    }

    /**
     * @param RegisterUserCommand $command
     * @throws InvalidEmailException
     * @throws InvalidPasswordException
     */
    public function __invoke(RegisterUserCommand $command): void
    {
        $user = $this->userFactory->register(
            $command->uuid,
            $command->email,
            $command->password,
            $command->firstName,
            $command->lastName,
            $command->role,
            $this->clock->now()
        );

        $this->userStorage->store($user);

    }
}