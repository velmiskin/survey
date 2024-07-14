<?php

declare(strict_types=1);


namespace App\Tests\Integration\User;

use App\Component\User\Application\Command\CreateUserCommand;
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
        $password = 'password';
        $role = 'ROLE_PATIENT';

        $command = new CreateUserCommand($uuid, $firstName, $lastName, $email, $password, $role);
        $this->commandBus->dispatch($command);

        $this->bus('command.bus')->dispatched()->assertCount(1);
    }

    public function testChangeUserPasswordCommand(): void
    {
        $uuid = Uuid::uuid4();
        $password = 'password';
        $newPassword = 'newPassword';

        //@todo implement test
    }

}