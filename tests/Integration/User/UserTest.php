<?php

declare(strict_types=1);


namespace App\Tests\Integration\User;

use App\Common\Infrastructure\DataFixtures\UserFixtures;
use App\Component\User\Application\Command\ChangeUserPasswordCommand;
use App\Component\User\Application\Command\RegisterUserCommand;
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

    public function testChangeUserPasswordCommand(): void
    {
        $this->databaseTool->loadFixtures([
            UserFixtures::class,
        ]);
        $command = new ChangeUserPasswordCommand(Uuid::fromString('f1b5f3b3-7f7b-4b8b-8b1e-3e1f0f3f3f3f'), 'newPassword');
        $this->commandBus->dispatch($command);
        $this->bus('command.bus')->dispatched()->assertCount(1);
        $this->bus('event.bus')->dispatched()->assertCount(1);

    }

}