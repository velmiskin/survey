<?php

declare(strict_types=1);

namespace App\Component\User\Application\Command;

use App\Common\Application\Command\CommandInterface;
use Ramsey\Uuid\UuidInterface;

final readonly class CreateUserCommand implements CommandInterface
{
    public function __construct(
        public UuidInterface $uuid,
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $password,
        public string $role,
    ) {
    }
}
