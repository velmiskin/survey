<?php

declare(strict_types=1);

namespace App\Component\User\Application\CommandHandler;

use App\Common\Application\Bus\EventBusInterface;
use App\Common\Application\Command\CommandHandlerInterface;
use App\Common\Application\Command\CommandInterface;
use App\Common\Domain\ValueObject\Email;
use App\Component\User\Application\Command\CreateUserCommand;
use App\Component\User\Domain\Entity\User;
use App\Component\User\Domain\Enum\Role as RoleEnum;
use App\Component\User\Domain\Exception\InvalidEmailException;
use App\Component\User\Domain\Exception\InvalidPasswordException;
use App\Component\User\Domain\Specification\UniqueEmailSpecificationInterface;
use App\Component\User\Domain\Storage\UserStorageInterface;
use App\Component\User\Domain\ValueObject\Password;
use App\Component\User\Domain\ValueObject\Role;
use Symfony\Component\Clock\ClockInterface;

final readonly class CreateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserStorageInterface $userStorage,
        private ClockInterface $clock,
        private UniqueEmailSpecificationInterface $uniqueEmailSpecification,
        private EventBusInterface $eventBus,
    ) {
    }

    /**
     * @param CreateUserCommand $command
     * @throws InvalidEmailException
     * @throws InvalidPasswordException
     */
    public function __invoke(CommandInterface $command): void
    {
        $user = new User(
            $command->uuid,
            new Email($command->email),
            $command->firstName,
            $command->lastName,
            new Password($command->password),
            new Role(RoleEnum::from($command->role)),
            $this->clock->now(),
            $this->uniqueEmailSpecification
        );

        $this->userStorage->store($user);

        $events = $user->pullEvents();

        if (count($events) === 0) {
            return;
        }

        $this->eventBus->dispatchMany($user->pullEvents());
    }
}