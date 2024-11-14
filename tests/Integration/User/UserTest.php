<?php

declare(strict_types=1);


namespace App\Tests\Integration\User;

use App\Component\User\Application\Command\ChangeUserPasswordCommand;
use App\Component\User\Application\Command\RegisterUserCommand;
use App\Component\User\Application\Exception\UserNotFoundException;
use App\Component\User\Domain\Exception\WrongOldPasswordException;
use App\Component\User\Infrastructure\Doctrine\Entity\User;
use App\Tests\Factory\UserFactory;
use App\Tests\Integration\AbstractTestCase;
use Ramsey\Uuid\Uuid;

final class UserTest extends AbstractTestCase
{
    public function testCreateUserCommand(): void
    {
        $uuid = Uuid::uuid4();
        $firstName = 'John';
        $lastName = 'Doe';
        $email = 'test@example.com';
        $password = 'password8';
        $role = 'ROLE_PATIENT';

        $command = new RegisterUserCommand($uuid, $firstName, $lastName, $email, $password, $role);
        $this->commandBus->dispatch($command);
        $this->bus('command.bus')->dispatched()->assertCount(1);
    }

    public function testCreateUserCommandWithExistingEmail(): void
    {
        /** @var User $user */
        $user = UserFactory::createOne();

        $command = new RegisterUserCommand(Uuid::uuid4(), 'John', 'Doe', $user->getEmail(), 'password8', 'ROLE_PATIENT');

        $this->expectExceptionMessage('User with this email already exists');
        $this->commandBus->dispatch($command);
    }

    public function testChangeUserPasswordCommand(): void
    {
        /** @var User $user */
        $user = UserFactory::createOne();

        $command = new ChangeUserPasswordCommand($user->getId(), $user->getPassword(), uniqid('', true));
        $this->commandBus->dispatch($command);
        $this->bus('command.bus')->dispatched()->assertCount(1);
        $this->bus('event.bus')->dispatched()->assertCount(1);

        $updatedUser = $this->entityManager->find(User::class, $user->getId());

        self::assertNotEquals($user->getPassword(), $updatedUser->getPassword());
    }

    public function testChangeUserPasswordCommandWithWrongOldPassword(): void
    {
        /** @var User $user */
        $user = UserFactory::createOne();

        $command = new ChangeUserPasswordCommand($user->getId(), 'wrongOldPassword', 'newPassword8');

        $this->expectExceptionMessage(WrongOldPasswordException::MESSAGE);
        $this->commandBus->dispatch($command);
    }

    public function testChangeUserPasswordCommandWithNonExistingUser(): void
    {
        $command = new ChangeUserPasswordCommand(Uuid::uuid4(), 'oldPassword', 'newPassword8');

        $this->expectExceptionMessage(UserNotFoundException::MESSAGE);
        $this->commandBus->dispatch($command);
    }

}